<?php

namespace app\helpers;

class ElevenLabsAgent
{

    public static function widget($agent_id)
    {

        return "
    <elevenlabs-convai agent-id='$agent_id'></elevenlabs-convai>


<script
        src='https://unpkg.com/@elevenlabs/convai-widget-embed'
        async
        type='text/javascript'
></script>
";

    }

}