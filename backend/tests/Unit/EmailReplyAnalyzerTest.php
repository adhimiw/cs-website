<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Mail\EmailReplyAnalyzer;

class EmailReplyAnalyzerTest extends TestCase
{
    public function test_it_detects_a_standard_reply_with_header_and_subject()
    {
        $headers = "From: client@example.com\r\nIn-Reply-To: <abc1234@climbsphere.ai>\r\nSubject: Re: Discovery Session\r\n";
        $subject = "Re: Discovery Session";
        $body = "Yes, sounds good! See you tomorrow at 3 PM.\r\n\r\nOn Sep 15, 2026, at 2:00 PM, ClimbSphere wrote:\r\n> Hi Client, looking forward to speaking!";

        $analysis = EmailReplyAnalyzer::analyze($headers, $subject, $body, "client@example.com");

        $this->assertTrue($analysis['is_reply']);
        $this->assertFalse($analysis['is_automated_or_loop']);
        $this->assertGreaterThanOrEqual(0.7, $analysis['confidence_score']);
        $this->assertContains('header_in_reply_to', $analysis['signals']);
        $this->assertContains('subject_re_prefix', $analysis['signals']);
        $this->assertEquals("Yes, sounds good! See you tomorrow at 3 PM.", $analysis['clean_reply_body']);
        $this->assertEquals("confirmation", $analysis['intent']);
        $this->assertEquals("Discovery Session", $analysis['root_subject']);
        $this->assertEquals("Re: Discovery Session", $analysis['reply_subject']);
    }

    public function test_it_detects_outlook_style_reply_and_strips_original_message()
    {
        $headers = "From: director@acme.org\r\nSubject: RE: Custom AI Agents Proposal\r\n";
        $subject = "RE: Custom AI Agents Proposal";
        $body = "Could we reschedule our call to Friday at 4 PM?\n\n-----Original Message-----\nFrom: ClimbSphere [mailto:devloper@adhithanr.space]\nSent: Tuesday, September 15, 2026 10:00 AM\nTo: Director\nSubject: Custom AI Agents Proposal";

        $analysis = EmailReplyAnalyzer::analyze($headers, $subject, $body, "director@acme.org");

        $this->assertTrue($analysis['is_reply']);
        $this->assertContains('body_outlook_original_message', $analysis['signals']);
        $this->assertEquals("Could we reschedule our call to Friday at 4 PM?", $analysis['clean_reply_body']);
        $this->assertEquals("reschedule", $analysis['intent']);
    }

    public function test_it_identifies_a_fresh_inbound_inquiry_as_not_a_reply()
    {
        $headers = "From: newlead@startup.io\r\nSubject: Inquiry about ClimbSphere Services\r\n";
        $subject = "Inquiry about ClimbSphere Services";
        $body = "Hi team, we are interested in building automated customer support agents. How much does a pilot project cost?";

        $analysis = EmailReplyAnalyzer::analyze($headers, $subject, $body, "newlead@startup.io");

        $this->assertFalse($analysis['is_reply']);
        $this->assertEmpty($analysis['signals']);
        $this->assertEquals(0.0, $analysis['confidence_score']);
        $this->assertEquals("inquiry", $analysis['intent']);
        $this->assertEquals("Hi team, we are interested in building automated customer support agents. How much does a pilot project cost?", $analysis['clean_reply_body']);
    }

    public function test_it_detects_automated_mailer_daemon_and_self_loops()
    {
        // Test 1: Self-loop
        $selfAnalysis = EmailReplyAnalyzer::analyze(
            "From: devloper@adhithanr.space\r\nSubject: Re: Auto Alert\r\n",
            "Re: Auto Alert",
            "Sample text",
            "devloper@adhithanr.space",
            "devloper@adhithanr.space"
        );
        $this->assertTrue($selfAnalysis['is_automated_or_loop']);
        $this->assertFalse($selfAnalysis['is_reply']);

        // Test 2: Mailer-Daemon bounce
        $daemonAnalysis = EmailReplyAnalyzer::analyze(
            "From: MAILER-DAEMON@mail.hostinger.com\r\nSubject: Undelivered Mail Returned to Sender\r\n",
            "Undelivered Mail Returned to Sender",
            "Failed delivery",
            "mailer-daemon@mail.hostinger.com"
        );
        $this->assertTrue($daemonAnalysis['is_automated_or_loop']);
        $this->assertFalse($daemonAnalysis['is_reply']);

        // Test 3: Auto-Submitted auto-reply
        $autoReplyAnalysis = EmailReplyAnalyzer::analyze(
            "From: vacation@company.com\r\nAuto-Submitted: auto-replied\r\nSubject: Out of Office\r\n",
            "Out of Office",
            "I am currently on vacation.",
            "vacation@company.com"
        );
        $this->assertTrue($autoReplyAnalysis['is_automated_or_loop']);
        $this->assertFalse($autoReplyAnalysis['is_reply']);
    }

    public function test_it_cleans_signature_and_mobile_footers()
    {
        $body = "Thanks for the update. All looks fine.\n\n--\nJane Doe\nVP Engineering\nSent from my iPhone";
        $clean = EmailReplyAnalyzer::extractCleanReply($body);

        $this->assertEquals("Thanks for the update. All looks fine.", $clean);
    }
}
