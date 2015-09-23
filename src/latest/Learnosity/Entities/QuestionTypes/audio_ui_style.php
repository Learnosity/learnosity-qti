<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class audio_ui_style extends BaseQuestionTypeAttribute
{
    protected $fontsize;
    protected $download_link;
    protected $pause;
    protected $pause_recording;
    protected $play;
    protected $play_bubble;
    protected $progress_bar;
    protected $record_bubble;
    protected $seek;
    protected $start_recording;
    protected $stop_recording;
    protected $timer;
    protected $type;
    protected $volume_control;
    protected $volume_meter;
    protected $waveform;
    protected $responsive_layout;

    public function __construct()
    {
    }

    /**
     * Get Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     *
     * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function get_fontsize()
    {
        return $this->fontsize;
    }

    /**
     * Set Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     *
     * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function set_fontsize($fontsize)
    {
        $this->fontsize = $fontsize;
    }

    /**
     * Get Download link \
     * If true, a link to download the audio asset is displayed in the UI \
     *
     * @return boolean $download_link \
     */
    public function get_download_link()
    {
        return $this->download_link;
    }

    /**
     * Set Download link \
     * If true, a link to download the audio asset is displayed in the UI \
     *
     * @param boolean $download_link \
     */
    public function set_download_link($download_link)
    {
        $this->download_link = $download_link;
    }

    /**
     * Get Pause \
     * Defines whether the pause button is displayed in the UI \
     *
     * @return boolean $pause \
     */
    public function get_pause()
    {
        return $this->pause;
    }

    /**
     * Set Pause \
     * Defines whether the pause button is displayed in the UI \
     *
     * @param boolean $pause \
     */
    public function set_pause($pause)
    {
        $this->pause = $pause;
    }

    /**
     * Get Pause recording \
     * Defines whether the pause recording button is enabled \
     *
     * @return boolean $pause_recording \
     */
    public function get_pause_recording()
    {
        return $this->pause_recording;
    }

    /**
     * Set Pause recording \
     * Defines whether the pause recording button is enabled \
     *
     * @param boolean $pause_recording \
     */
    public function set_pause_recording($pause_recording)
    {
        $this->pause_recording = $pause_recording;
    }

    /**
     * Get Play \
     * Defines whether the play button is displayed in the UI \
     *
     * @return boolean $play \
     */
    public function get_play()
    {
        return $this->play;
    }

    /**
     * Set Play \
     * Defines whether the play button is displayed in the UI \
     *
     * @param boolean $play \
     */
    public function set_play($play)
    {
        $this->play = $play;
    }

    /**
     * Get Play bubble \
     * Defines whether playback bubble is displayed in the UI, only available for 'button' type \
     *
     * @return boolean $play_bubble \
     */
    public function get_play_bubble()
    {
        return $this->play_bubble;
    }

    /**
     * Set Play bubble \
     * Defines whether playback bubble is displayed in the UI, only available for 'button' type \
     *
     * @param boolean $play_bubble \
     */
    public function set_play_bubble($play_bubble)
    {
        $this->play_bubble = $play_bubble;
    }

    /**
     * Get Progress bar \
     * Defines whether the progress bar is displayed in the UI \
     *
     * @return boolean $progress_bar \
     */
    public function get_progress_bar()
    {
        return $this->progress_bar;
    }

    /**
     * Set Progress bar \
     * Defines whether the progress bar is displayed in the UI \
     *
     * @param boolean $progress_bar \
     */
    public function set_progress_bar($progress_bar)
    {
        $this->progress_bar = $progress_bar;
    }

    /**
     * Get Record bubble \
     * Defines whether recording bubble is displayed in the UI, only available for 'button' type \
     *
     * @return boolean $record_bubble \
     */
    public function get_record_bubble()
    {
        return $this->record_bubble;
    }

    /**
     * Set Record bubble \
     * Defines whether recording bubble is displayed in the UI, only available for 'button' type \
     *
     * @param boolean $record_bubble \
     */
    public function set_record_bubble($record_bubble)
    {
        $this->record_bubble = $record_bubble;
    }

    /**
     * Get Seek \
     * Defines whether the user can change the play position \
     *
     * @return boolean $seek \
     */
    public function get_seek()
    {
        return $this->seek;
    }

    /**
     * Set Seek \
     * Defines whether the user can change the play position \
     *
     * @param boolean $seek \
     */
    public function set_seek($seek)
    {
        $this->seek = $seek;
    }

    /**
     * Get Start recording \
     * Defines whether the start recording button is enabled \
     *
     * @return boolean $start_recording \
     */
    public function get_start_recording()
    {
        return $this->start_recording;
    }

    /**
     * Set Start recording \
     * Defines whether the start recording button is enabled \
     *
     * @param boolean $start_recording \
     */
    public function set_start_recording($start_recording)
    {
        $this->start_recording = $start_recording;
    }

    /**
     * Get Stop Recording \
     * Defines whether the pause recording button is enabled \
     *
     * @return boolean $stop_recording \
     */
    public function get_stop_recording()
    {
        return $this->stop_recording;
    }

    /**
     * Set Stop Recording \
     * Defines whether the pause recording button is enabled \
     *
     * @param boolean $stop_recording \
     */
    public function set_stop_recording($stop_recording)
    {
        $this->stop_recording = $stop_recording;
    }

    /**
     * Get Timer \
     * Defines whether the timer is displayed in the UI \
     *
     * @return boolean $timer \
     */
    public function get_timer()
    {
        return $this->timer;
    }

    /**
     * Set Timer \
     * Defines whether the timer is displayed in the UI \
     *
     * @param boolean $timer \
     */
    public function set_timer($timer)
    {
        $this->timer = $timer;
    }

    /**
     * Get Layout \
     * Defines the rendering type of audio  question. Values: "block", "button". \
     *
     * @return string $type \
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set Layout \
     * Defines the rendering type of audio  question. Values: "block", "button". \
     *
     * @param string $type \
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Get Volume Control \
     * Defines whether the volume control is displayed in the UI \
     *
     * @return boolean $volume_control \
     */
    public function get_volume_control()
    {
        return $this->volume_control;
    }

    /**
     * Set Volume Control \
     * Defines whether the volume control is displayed in the UI \
     *
     * @param boolean $volume_control \
     */
    public function set_volume_control($volume_control)
    {
        $this->volume_control = $volume_control;
    }

    /**
     * Get Volume Meter \
     * Defines whether the volume meter is displayed in the UI \
     *
     * @return boolean $volume_meter \
     */
    public function get_volume_meter()
    {
        return $this->volume_meter;
    }

    /**
     * Set Volume Meter \
     * Defines whether the volume meter is displayed in the UI \
     *
     * @param boolean $volume_meter \
     */
    public function set_volume_meter($volume_meter)
    {
        $this->volume_meter = $volume_meter;
    }

    /**
     * Get Waveform \
     * Defines whether the waveform is displayed in the UI \
     *
     * @return boolean $waveform \
     */
    public function get_waveform()
    {
        return $this->waveform;
    }

    /**
     * Set Waveform \
     * Defines whether the waveform is displayed in the UI \
     *
     * @param boolean $waveform \
     */
    public function set_waveform($waveform)
    {
        $this->waveform = $waveform;
    }

    /**
     * Get Responsive Layout \
     * Defines whether the feature item size can be scaled responsively \
     *
     * @return boolean $responsive_layout \
     */
    public function get_responsive_layout()
    {
        return $this->responsive_layout;
    }

    /**
     * Set Responsive Layout \
     * Defines whether the feature item size can be scaled responsively \
     *
     * @param boolean $responsive_layout \
     */
    public function set_responsive_layout($responsive_layout)
    {
        $this->responsive_layout = $responsive_layout;
    }


}

