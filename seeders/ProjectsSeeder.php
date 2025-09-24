<?php

namespace Seeders;
require_once 'Seeder.php';

class ProjectsSeeder extends Seeder implements SeederInterface
{
    public function run()
    {
        $this->insert('projects', [
            'project_id' => '025TRPCR',
            'name' => 'No Scammie Web & Mobile App',
            'user_id' => '1',
            'description' =>'
<h2>Overview:</h2>
<p>No Scammie is a user-friendly, simplified, and free scam prevention application designed to protect users from fraudulent activities. The app leverages real-time analysis to detect potential scams and provides educational resources along with community-driven insights. Its intuitive interface ensures that users of all technical abilities can easily navigate and utilize its features.</p>

<h2>Features:</h2>
<ul>
    <li>Real-time scam detection and prevention</li>
    <li>Educational resources on scam awareness and avoidance</li>
    <li>Community-driven insights and shared experiences</li>
    <li>Simple and accessible interface for all users</li>
    <li>Continuous updates and improvements based on user feedback and the latest scam trends</li>
</ul>

<h2>Project Documentation:</h2>
<p>All features, functionalities, technical requirements, and detailed specifications for the No Scammie app are comprehensively outlined in the project documentation. This documentation serves as the primary reference point for the development, implementation, and delivery of the project. It contains the full scope of work, timeline, milestones, and expected deliverables.</p>

<p>By signing this contract, both parties acknowledge that they have reviewed and agree to the terms, conditions, and details as specified in the project documentation. Any modifications, updates, or additional features will be addressed through change requests and amendments to the documentation as agreed upon by both parties.</p>
',
            'status'=>'active',
            'pricing'=>'20500',

        ]);
    }
}
