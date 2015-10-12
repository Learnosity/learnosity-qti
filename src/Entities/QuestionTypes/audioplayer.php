<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class audioplayer extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $src;
    protected $player;
    protected $playback_limit;
    protected $waveform;
    protected $format;
    protected $ui_style;
    protected $heading;
    protected $caption;
    
    public function __construct(
                    $type,
                                $src
                        )
    {
                $this->type = $type;
                $this->src = $src;
            }

    /**
    * Get Feature Type \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Feature Type \
    *  \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get Metadata \
    * Additional data for the audio player \
    * @return audioplayer_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set Metadata \
    * Additional data for the audio player \
    * @param audioplayer_metadata $metadata \
    */
    public function set_metadata (audioplayer_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Source Url \
    * Embed an audio clip as an additional feature to the current content. You can use your own audio or audio hosted on the I
	nternet. \
    * @return string $src \
    */
    public function get_src() {
        return $this->src;
    }

    /**
    * Set Source Url \
    * Embed an audio clip as an additional feature to the current content. You can use your own audio or audio hosted on the I
	nternet. \
    * @param string $src \
    */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
    * Get Player Type \
    * Specify the type of audio player between the options: block, button, minimal, bar.</ul> \
    * @return string $player \
    */
    public function get_player() {
        return $this->player;
    }

    /**
    * Set Player Type \
    * Specify the type of audio player between the options: block, button, minimal, bar.</ul> \
    * @param string $player \
    */
    public function set_player ($player) {
        $this->player = $player;
    }

    /**
    * Get Playback Limit \
    * Number of play attempts the user has, with 0 being unlimited. \
    * @return number $playback_limit \
    */
    public function get_playback_limit() {
        return $this->playback_limit;
    }

    /**
    * Set Playback Limit \
    * Number of play attempts the user has, with 0 being unlimited. \
    * @param number $playback_limit \
    */
    public function set_playback_limit ($playback_limit) {
        $this->playback_limit = $playback_limit;
    }

    /**
    * Get Waveform URI \
    * URI of the waveform to display. \
    * @return string $waveform \
    */
    public function get_waveform() {
        return $this->waveform;
    }

    /**
    * Set Waveform URI \
    * URI of the waveform to display. \
    * @param string $waveform \
    */
    public function set_waveform ($waveform) {
        $this->waveform = $waveform;
    }

    /**
    * Get Format \
    * Allows specifying the audio format instead of relying on the file extension on <em>src</em>. <br /> If <em>format</em> i
	s not defined and <em>src</em> does not have a file extension, the format is assumed to be "mp3". \
    * @return string $format \
    */
    public function get_format() {
        return $this->format;
    }

    /**
    * Set Format \
    * Allows specifying the audio format instead of relying on the file extension on <em>src</em>. <br /> If <em>format</em> i
	s not defined and <em>src</em> does not have a file extension, the format is assumed to be "mp3". \
    * @param string $format \
    */
    public function set_format ($format) {
        $this->format = $format;
    }

    /**
    * Get UI style \
    * Object that defines the different UI styles of the audio player. \
    * @return audioplayer_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set UI style \
    * Object that defines the different UI styles of the audio player. \
    * @param audioplayer_ui_style $ui_style \
    */
    public function set_ui_style (audioplayer_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Heading \
    * Heading of the audio player \
    * @return string $heading \
    */
    public function get_heading() {
        return $this->heading;
    }

    /**
    * Set Heading \
    * Heading of the audio player \
    * @param string $heading \
    */
    public function set_heading ($heading) {
        $this->heading = $heading;
    }

    /**
    * Get Caption \
    * Description of the audio being played \
    * @return string $caption \
    */
    public function get_caption() {
        return $this->caption;
    }

    /**
    * Set Caption \
    * Description of the audio being played \
    * @param string $caption \
    */
    public function set_caption ($caption) {
        $this->caption = $caption;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

