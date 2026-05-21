<?php

namespace App\AI\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Laravel\Ai\Messages\Message;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use App\Models\ChatSession;

class LeadChatAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    protected ?ChatSession $chatSession = null;

    public function __construct(?ChatSession $chatSession = null)
    {
        $this->chatSession = $chatSession;
    }

    public function instructions(): string
    {
        return "You are the friendly, professional lead qualification assistant for ClimbSphere, an agency specializing in web development, mobile apps, and custom software. " .
               "Your objectives:\n" .
               "1. Engage in friendly conversation, answering questions about ClimbSphere's services (e.g., custom web portals, mobile apps, SaaS, UI/UX, AI integrations).\n" .
               "2. Qualify the visitor as a potential lead by progressively collecting: name, email, phone, company, project type/idea, budget, and timeline.\n" .
               "3. Be natural and conversational. DO NOT dump all questions at once. Ask questions one at a time when appropriate.\n" .
               "4. Once you have at least the visitor's name, email, and a basic project plan or idea, mark the lead as qualified. Once qualified, let them know that the team will reach out via email within 24 hours.";
    }

    public function messages(): array
    {
        if (!$this->chatSession) {
            return [];
        }

        // Return preceding messages sorted by chronological order (excluding system messages if you wish)
        return $this->chatSession->messages()
            ->orderBy('id', 'asc')
            ->get()
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
