<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class videoplayer extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $simplefeature_id;
    protected $player_type;
    protected $heading;
    protected $caption;
    protected $crossorigin;
    protected $src;
    protected $poster;
    protected $width;
    protected $height;
    protected $no-controls;
    protected $text_alternative;
    protected $allow_retries;
    
    public function __construct(
                    $type,
                                $player_type,
                                $src
                        )
    {
                $this->type = $type;
                $this->player_type = $player_type;
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
     * Additional data for the video player \
     * @return videoplayer_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set Metadata \
     * Additional data for the video player \
     * @param videoplayer_metadata $metadata \
     */
    public function set_metadata (videoplayer_metadata $metadata) {
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
     * Get Video type \
     * Defines the type of video player you want to create. \
     * @return string $player_type \
     */
    public function get_player_type() {
        return $this->player_type;
    }

    /**
     * Set Video type \
     * Defines the type of video player you want to create. \
     * @param string $player_type \
     */
    public function set_player_type ($player_type) {
        $this->player_type = $player_type;
    }

    /**
     * Get Heading \
     * Heading of the video \
     * @return string $heading \
     */
    public function get_heading() {
        return $this->heading;
    }

    /**
     * Set Heading \
     * Heading of the video \
     * @param string $heading \
     */
    public function set_heading ($heading) {
        $this->heading = $heading;
    }

    /**
     * Get Summary \
     * Description of the video being played \
     * @return string $caption \
     */
    public function get_caption() {
        return $this->caption;
    }

    /**
     * Set Summary \
     * Description of the video being played \
     * @param string $caption \
     */
    public function set_caption ($caption) {
        $this->caption = $caption;
    }

    /**
     * Get Summary \
     * Defines the crossorigin attribute for the video player. Values must be either <code>"use-credentials"</code>, <code>"ano
	 * nymous"</code> or <code>"no-cors"</code>. To remove the attribute, <code>"no-cors"</code> must be used. Not setting a va
	 * lue, or setting an invalid value will result in <code>"anonymous"</code> for backwards compatibility. \
     * @return string $crossorigin \
     */
    public function get_crossorigin() {
        return $this->crossorigin;
    }

    /**
     * Set Summary \
     * Defines the crossorigin attribute for the video player. Values must be either <code>"use-credentials"</code>, <code>"ano
	 * nymous"</code> or <code>"no-cors"</code>. To remove the attribute, <code>"no-cors"</code> must be used. Not setting a va
	 * lue, or setting an invalid value will result in <code>"anonymous"</code> for backwards compatibility. \
     * @param string $crossorigin \
     */
    public function set_crossorigin ($crossorigin) {
        $this->crossorigin = $crossorigin;
    }

    /**
     * Get Source URL \
     * A link to your hosted video. You can upload an MP4 file (H.264/MPEG-4 is the only supported video format, this is the mo
	 * st commonly used and supported video format). \
     * @return string $src \
     */
    public function get_src() {
        return $this->src;
    }

    /**
     * Set Source URL \
     * A link to your hosted video. You can upload an MP4 file (H.264/MPEG-4 is the only supported video format, this is the mo
	 * st commonly used and supported video format). \
     * @param string $src \
     */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
     * Get Poster image \
     * A cover image for the video before it starts playing. For iOS devices, ensure you include a poster image as the video wi
	 * ll not be preloaded to show the first frame. \
     * @return string $poster \
     */
    public function get_poster() {
        return $this->poster;
    }

    /**
     * Set Poster image \
     * A cover image for the video before it starts playing. For iOS devices, ensure you include a poster image as the video wi
	 * ll not be preloaded to show the first frame. \
     * @param string $poster \
     */
    public function set_poster ($poster) {
        $this->poster = $poster;
    }

    /**
     * Get Width (px) \
     * The width of the video player in pixels or percentage. <span class="label label-important">IMPORTANT</span> Responsive d
	 * esign with percentage height and width is not supported for Vimeo videos. \
     * @return string $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     * The width of the video player in pixels or percentage. <span class="label label-important">IMPORTANT</span> Responsive d
	 * esign with percentage height and width is not supported for Vimeo videos. \
     * @param string $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get Height (px) \
     * The height of the video player in pixels or percentage. <span class="label label-important">IMPORTANT</span> Responsive 
	 * design with percentage height and width is not supported for Vimeo videos. \
     * @return string $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     * The height of the video player in pixels or percentage. <span class="label label-important">IMPORTANT</span> Responsive 
	 * design with percentage height and width is not supported for Vimeo videos. \
     * @param string $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Hide controls \
     * Set to <strong>true</strong> to remove the video controls from the player. \
     * @return boolean $no-controls \
     */
    public function get_no-controls() {
        return $this->no-controls;
    }

    /**
     * Set Hide controls \
     * Set to <strong>true</strong> to remove the video controls from the player. \
     * @param boolean $no-controls \
     */
    public function set_no-controls ($no-controls) {
        $this->no-controls = $no-controls;
    }

    /**
     * Get text_alternative \
     *  \
     * @return videoplayer_text_alternative $text_alternative \
     */
    public function get_text_alternative() {
        return $this->text_alternative;
    }

    /**
     * Set text_alternative \
     *  \
     * @param videoplayer_text_alternative $text_alternative \
     */
    public function set_text_alternative (videoplayer_text_alternative $text_alternative) {
        $this->text_alternative = $text_alternative;
    }

    /**
     * Get Allow Retries \
     * Specifies whether to allow retries when loading video. \
     * @return boolean $allow_retries \
     */
    public function get_allow_retries() {
        return $this->allow_retries;
    }

    /**
     * Set Allow Retries \
     * Specifies whether to allow retries when loading video. \
     * @param boolean $allow_retries \
     */
    public function set_allow_retries ($allow_retries) {
        $this->allow_retries = $allow_retries;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

