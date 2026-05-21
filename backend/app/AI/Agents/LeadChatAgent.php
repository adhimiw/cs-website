<?php

namespace App\AI\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Conversational;
use App\AI\Tools\ClimbSphereKnowledgeSearch;
use Laravel\Ai\Promptable;
use Laravel\Ai\Messages\Message;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use App\Models\ChatSession;

class LeadChatAgent implements Agent, HasStructuredOutput, Conversational
{
    use Promptable;

    protected ?ChatSession $chatSession = null;
    protected ?string $retrievedContext = null;

    public function __construct(?ChatSession $chatSession = null, ?string $retrievedContext = null)
    {
        $this->chatSession = $chatSession;
        $this->retrievedContext = $retrievedContext;
    }

    public function instructions(): string
    {
        $instructions = "You are the friendly, professional lead qualification assistant for ClimbSphere, an agency specializing in web development, mobile apps, and custom software. " .
               "Your objectives:\n" .
               "1. Engage in friendly conversation, answering questions about ClimbSphere's services (e.g., custom web portals, mobile apps, SaaS, UI/UX, AI integrations).\n" .
               "2. Qualify the visitor as a potential lead by progressively collecting: name, email, phone, company, project type/idea, budget, and timeline.\n" .
               "3. Be natural and conversational. DO NOT dump all questions at once. Ask questions one at a time when appropriate.\n" .
               "4. Once you have at least the visitor's name, email, and a basic project plan or idea, mark the lead as qualified. Once qualified, let them know that the team will reach out via email within 24 hours.";

        // Retrieve existing lead details from DB to keep the conversation stateful and prevent details loss
        $existingLead = null;
        if ($this->chatSession) {
            $existingLead = \App\Models\Lead::where('chat_session_id', $this->chatSession->id)->first();
        }

        if ($existingLead) {
            $knownDetails = [];
            foreach (['name', 'email', 'phone', 'company', 'project_type', 'plan_or_idea', 'budget', 'timeline'] as $field) {
                if ($existingLead->{$field} !== '' && $existingLead->{$field} !== null) {
                    $knownDetails[] = "- " . ucfirst(str_replace('_', ' ', $field)) . ": " . $existingLead->{$field};
                }
            }
            if (!empty($knownDetails)) {
                $instructions .= "\n\nAlready captured details about this visitor (DO NOT ask for these again. Make sure to keep returning these same values in the 'extracted' output to prevent losing them):\n" . implode("\n", $knownDetails);
            }
        }

        if ($this->retrievedContext) {
            $instructions .= "\n\nUse the following factual context retrieved from ClimbSphere's database to answer the visitor's questions accurately:\n" . $this->retrievedContext;
        }

        return $instructions;
    }

    public function messages(): array
    {
        if (!$this->chatSession) {
            return [];
        }

        $messages = $this->chatSession->messages()
            ->orderBy('id', 'asc')
            ->get();

        // Since the current user message is already saved in the database before calling prompt(),
        // we exclude the last user message to avoid duplicate consecutive user messages.
        if ($messages->isNotEmpty() && $messages->last()->role === 'user') {
            $messages->pop();
        }

        return $messages
            ->map(function ($m) {
                return new Message($m->role, $m->content);
            })
            ->all();
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'reply' => $schema->string()->description('Your friendly natural-language response to the visitor. Formatted with paragraphs or bullet points if necessary. Always answer their question first before prompting for new lead info.')->required(),
            'extracted' => $schema->object(function ($extractedSchema) {
                return [
                    'name' => $extractedSchema->string()->nullable()->description('The visitor\'s name.'),
                    'email' => $extractedSchema->string()->nullable()->description('The visitor\'s email address. Format as a valid email if captured.'),
                    'phone' => $extractedSchema->string()->nullable()->description('The visitor\'s telephone or phone number.'),
                    'company' => $extractedSchema->string()->nullable()->description('The company or organization name.'),
                    'project_type' => $extractedSchema->string()->nullable()->description('The type of project (e.g., mobile app, custom website, e-commerce, software migration).'),
                    'plan_or_idea' => $extractedSchema->string()->nullable()->description('Summary of their project idea or plan.'),
                    'budget' => $extractedSchema->string()->nullable()->description('Estimated budget category or range if mentioned.'),
                    'timeline' => $extractedSchema->string()->nullable()->description('Project timeline or launch window (e.g., 3 months, immediate).'),
                ];
            })->required()->description('Extracted lead attributes from the conversation. Accumulate previously extracted parameters from context unless updated by user.'),
            'lead_status' => $schema->string()->enum(['new', 'qualified'])->required()->description('Status of the lead. Set to "qualified" ONLY if name, email, and a basic project description are all present.'),
            'send_ack_email' => $schema->boolean()->required()->description('Set to true ONLY in the exact turn where the lead status becomes qualified, indicating we should trigger the acknowledgement email dispatch.'),
        ];
    }
}
