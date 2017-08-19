<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class audioplayer extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $simplefeature_id;
    protected $src;
    protected $player;
    protected $playback_limit;
    protected $waveform;
    protected $format;
    
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
    * Get metadata \
    *  \
    * @return object $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param object $metadata \
    */
    public function set_metadata ($metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Simple feature reference \
    *  \
    * @return string $simplefeature_id \
    */
    public function get_simplefeature_id() {
        return $this->simplefeature_id;
    }

    /**
    * Set Simple feature reference \
    *  \
    * @param string $simplefeature_id \
    */
    public function set_simplefeature_id ($simplefeature_id) {
        $this->simplefeature_id = $simplefeature_id;
    }

    /**
    * Get Source URL \
    * Embed an audio clip as an additional feature to the current content. You can use your own audio or audio hosted on the I
	nternet. \
    * @return string $src \
    */
    public function get_src() {
        return $this->src;
    }

    /**
    * Set Source URL \
    * Embed an audio clip as an additional feature to the current content. You can use your own audio or audio hosted on the I
	nternet. \
    * @param string $src \
    */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
    * Get Player type \
    * Specify the type of audio player between the options: block, button, minimal, bar.</ul> \
    * @return string $player \
    */
    public function get_player() {
        return $this->player;
    }

    /**
    * Set Player type \
    * Specify the type of audio player between the options: block, button, minimal, bar.</ul> \
    * @param string $player \
    */
    public function set_player ($player) {
        $this->player = $player;
    }

    /**
    * Get Playback limit \
    * Number of play attempts the user has, with 0 being unlimited. \
    * @return number $playback_limit \
    */
    public function get_playback_limit() {
        return $this->playback_limit;
    }

    /**
    * Set Playback limit \
    * Number of play attempts the user has, with 0 being unlimited. \
    * @param number $playback_limit \
    */
    public function set_playback_limit ($playback_limit) {
        $this->playback_limit = $playback_limit;
    }

    /**
    * Get Waveform image \
    * URI of the waveform to display. \
    * @return string $waveform \
    */
    public function get_waveform() {
        return $this->waveform;
    }

    /**
    * Set Waveform image \
    * URI of the waveform to display. \
    * @param string $waveform \
    */
    public function set_waveform ($waveform) {
        $this->waveform = $waveform;
    }

    /**
    * Get File format \
    * Allows specifying the audio format instead of relying on the file extension on <em>src</em>. <br /> If <em>format</em> i
	s not defined and <em>src</em> does not have a file extension, the format is assumed to be "mp3". \
    * @return string $format \
    */
    public function get_format() {
        return $this->format;
    }

    /**
    * Set File format \
    * Allows specifying the audio format instead of relying on the file extension on <em>src</em>. <br /> If <em>format</em> i
	s not defined and <em>src</em> does not have a file extension, the format is assumed to be "mp3". \
    * @param string $format \
    */
    public function set_format ($format) {
        $this->format = $format;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

