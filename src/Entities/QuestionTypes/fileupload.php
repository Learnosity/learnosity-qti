<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class fileupload extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $photo_capture;
    protected $max_width;
    protected $max_files;
    protected $allow_pdf;
    protected $allow_jpg;
    protected $allow_gif;
    protected $allow_png;
    protected $allow_csv;
    protected $allow_rtf;
    protected $allow_txt;
    protected $allow_xps;
    protected $allow_zip;
    protected $allow_ms_word;
    protected $allow_ms_excel;
    protected $allow_ms_powerpoint;
    protected $allow_ms_publisher;
    protected $allow_open_office;
    protected $allow_video;
    protected $allow_matlab;
    protected $allow_altera_quartus;
    protected $allow_verilog;
    protected $allow_c;
    protected $allow_h;
    protected $allow_s;
    protected $allow_v;
    protected $allow_cpp;
    protected $allow_assembly;
    protected $allow_labview;
    protected $allow_psd;
    protected $allow_ai;
    protected $math_renderer;
    
    public function __construct(
                    $type,
                                $max_files
                        )
    {
                $this->type = $type;
                $this->max_files = $max_files;
            }

    /**
     * Get Contains math \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     * @return boolean $is_math \
     */
    public function get_is_math() {
        return $this->is_math;
    }

    /**
     * Set Contains math \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     * @param boolean $is_math \
     */
    public function set_is_math ($is_math) {
        $this->is_math = $is_math;
    }

    /**
     * Get metadata \
     *  \
     * @return fileupload_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param fileupload_metadata $metadata \
     */
    public function set_metadata (fileupload_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
     * Get Stimulus \
     * The question stimulus. This can include text, tables, images, resources and LaTeX entered via the Math Editor. \
     * @return string $stimulus \
     */
    public function get_stimulus() {
        return $this->stimulus;
    }

    /**
     * Set Stimulus \
     * The question stimulus. This can include text, tables, images, resources and LaTeX entered via the Math Editor. \
     * @param string $stimulus \
     */
    public function set_stimulus ($stimulus) {
        $this->stimulus = $stimulus;
    }

    /**
     * Get Stimulus (review only) \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	 * red <strong>above</strong> the response area. Supports embedded <a href="https://docs.learnosity.com/assessment/question
	 * s/knowledgebase/customfeatures" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     * @return string $stimulus_review \
     */
    public function get_stimulus_review() {
        return $this->stimulus_review;
    }

    /**
     * Set Stimulus (review only) \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	 * red <strong>above</strong> the response area. Supports embedded <a href="https://docs.learnosity.com/assessment/question
	 * s/knowledgebase/customfeatures" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     * @param string $stimulus_review \
     */
    public function set_stimulus_review ($stimulus_review) {
        $this->stimulus_review = $stimulus_review;
    }

    /**
     * Get Instructor stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 *  set to <code>true</code> on the activity. Supports embedded <a href="https://docs.learnosity.com/assessment/questions/f
	 * eaturetypes" target="_blank">Feature &lt;span&gt; tags</a>. \
     * @return string $instructor_stimulus \
     */
    public function get_instructor_stimulus() {
        return $this->instructor_stimulus;
    }

    /**
     * Set Instructor stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 *  set to <code>true</code> on the activity. Supports embedded <a href="https://docs.learnosity.com/assessment/questions/f
	 * eaturetypes" target="_blank">Feature &lt;span&gt; tags</a>. \
     * @param string $instructor_stimulus \
     */
    public function set_instructor_stimulus ($instructor_stimulus) {
        $this->instructor_stimulus = $instructor_stimulus;
    }

    /**
     * Get Question type \
     *  \
     * @return string $type \
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Set Question type \
     *  \
     * @param string $type \
     */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
     * Get ui_style \
     *  \
     * @return fileupload_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param fileupload_ui_style $ui_style \
     */
    public function set_ui_style (fileupload_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @return fileupload_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @param fileupload_validation $validation \
     */
    public function set_validation (fileupload_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Enable photo capture \
     * Allow the student to capture and upload a photo using the built-in camera of the device. \
     * @return boolean $photo_capture \
     */
    public function get_photo_capture() {
        return $this->photo_capture;
    }

    /**
     * Set Enable photo capture \
     * Allow the student to capture and upload a photo using the built-in camera of the device. \
     * @param boolean $photo_capture \
     */
    public function set_photo_capture ($photo_capture) {
        $this->photo_capture = $photo_capture;
    }

    /**
     * Get Max width \
     * Max width of the upload area. Define in em, px; or set to 'none' to stretch to full width of container. \
     * @return string $max_width \
     */
    public function get_max_width() {
        return $this->max_width;
    }

    /**
     * Set Max width \
     * Max width of the upload area. Define in em, px; or set to 'none' to stretch to full width of container. \
     * @param string $max_width \
     */
    public function set_max_width ($max_width) {
        $this->max_width = $max_width;
    }

    /**
     * Get Max files \
     * Select a value from the drop down to indicate the maximum amount of files that the student can upload. This value will b
	 * e visible to the student. \
     * @return number $max_files ie. 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12  \
     */
    public function get_max_files() {
        return $this->max_files;
    }

    /**
     * Set Max files \
     * Select a value from the drop down to indicate the maximum amount of files that the student can upload. This value will b
	 * e visible to the student. \
     * @param number $max_files ie. 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12  \
     */
    public function set_max_files ($max_files) {
        $this->max_files = $max_files;
    }

    /**
     * Get PDF \
     * Allow the student to upload .pdf files. \
     * @return boolean $allow_pdf \
     */
    public function get_allow_pdf() {
        return $this->allow_pdf;
    }

    /**
     * Set PDF \
     * Allow the student to upload .pdf files. \
     * @param boolean $allow_pdf \
     */
    public function set_allow_pdf ($allow_pdf) {
        $this->allow_pdf = $allow_pdf;
    }

    /**
     * Get JPG \
     * Allow the student to upload .jpg files. \
     * @return boolean $allow_jpg \
     */
    public function get_allow_jpg() {
        return $this->allow_jpg;
    }

    /**
     * Set JPG \
     * Allow the student to upload .jpg files. \
     * @param boolean $allow_jpg \
     */
    public function set_allow_jpg ($allow_jpg) {
        $this->allow_jpg = $allow_jpg;
    }

    /**
     * Get GIF \
     * Allow the student to upload .gif files. \
     * @return boolean $allow_gif \
     */
    public function get_allow_gif() {
        return $this->allow_gif;
    }

    /**
     * Set GIF \
     * Allow the student to upload .gif files. \
     * @param boolean $allow_gif \
     */
    public function set_allow_gif ($allow_gif) {
        $this->allow_gif = $allow_gif;
    }

    /**
     * Get PNG \
     * Allow the student to upload .png files. \
     * @return boolean $allow_png \
     */
    public function get_allow_png() {
        return $this->allow_png;
    }

    /**
     * Set PNG \
     * Allow the student to upload .png files. \
     * @param boolean $allow_png \
     */
    public function set_allow_png ($allow_png) {
        $this->allow_png = $allow_png;
    }

    /**
     * Get CSV \
     * Allow the student to upload .csv files. \
     * @return boolean $allow_csv \
     */
    public function get_allow_csv() {
        return $this->allow_csv;
    }

    /**
     * Set CSV \
     * Allow the student to upload .csv files. \
     * @param boolean $allow_csv \
     */
    public function set_allow_csv ($allow_csv) {
        $this->allow_csv = $allow_csv;
    }

    /**
     * Get RTF \
     * Allow the student to upload .rtf files. \
     * @return boolean $allow_rtf \
     */
    public function get_allow_rtf() {
        return $this->allow_rtf;
    }

    /**
     * Set RTF \
     * Allow the student to upload .rtf files. \
     * @param boolean $allow_rtf \
     */
    public function set_allow_rtf ($allow_rtf) {
        $this->allow_rtf = $allow_rtf;
    }

    /**
     * Get TXT \
     * Allow the student to upload .txt files. \
     * @return boolean $allow_txt \
     */
    public function get_allow_txt() {
        return $this->allow_txt;
    }

    /**
     * Set TXT \
     * Allow the student to upload .txt files. \
     * @param boolean $allow_txt \
     */
    public function set_allow_txt ($allow_txt) {
        $this->allow_txt = $allow_txt;
    }

    /**
     * Get XPS \
     * Allow the student to upload .xps files. \
     * @return boolean $allow_xps \
     */
    public function get_allow_xps() {
        return $this->allow_xps;
    }

    /**
     * Set XPS \
     * Allow the student to upload .xps files. \
     * @param boolean $allow_xps \
     */
    public function set_allow_xps ($allow_xps) {
        $this->allow_xps = $allow_xps;
    }

    /**
     * Get ZIP \
     * Allow the student to upload .zip files. \
     * @return boolean $allow_zip \
     */
    public function get_allow_zip() {
        return $this->allow_zip;
    }

    /**
     * Set ZIP \
     * Allow the student to upload .zip files. \
     * @param boolean $allow_zip \
     */
    public function set_allow_zip ($allow_zip) {
        $this->allow_zip = $allow_zip;
    }

    /**
     * Get Word \
     * Allow the student to upload Microsoft Word file types including: doc, docx, dot, dotx, docm, dotm. \
     * @return boolean $allow_ms_word \
     */
    public function get_allow_ms_word() {
        return $this->allow_ms_word;
    }

    /**
     * Set Word \
     * Allow the student to upload Microsoft Word file types including: doc, docx, dot, dotx, docm, dotm. \
     * @param boolean $allow_ms_word \
     */
    public function set_allow_ms_word ($allow_ms_word) {
        $this->allow_ms_word = $allow_ms_word;
    }

    /**
     * Get Excel \
     * Allow the student to upload Microsoft Excel file types including: xls, xlsx, xlt, xltm, xlsm. \
     * @return boolean $allow_ms_excel \
     */
    public function get_allow_ms_excel() {
        return $this->allow_ms_excel;
    }

    /**
     * Set Excel \
     * Allow the student to upload Microsoft Excel file types including: xls, xlsx, xlt, xltm, xlsm. \
     * @param boolean $allow_ms_excel \
     */
    public function set_allow_ms_excel ($allow_ms_excel) {
        $this->allow_ms_excel = $allow_ms_excel;
    }

    /**
     * Get Powerpoint \
     * Allow the student to upload Microsoft Powerpoint file types including: ppt, pps, pot, pptx, pptm, potx, potm, ppsx, ppsm
	 * . \
     * @return boolean $allow_ms_powerpoint \
     */
    public function get_allow_ms_powerpoint() {
        return $this->allow_ms_powerpoint;
    }

    /**
     * Set Powerpoint \
     * Allow the student to upload Microsoft Powerpoint file types including: ppt, pps, pot, pptx, pptm, potx, potm, ppsx, ppsm
	 * . \
     * @param boolean $allow_ms_powerpoint \
     */
    public function set_allow_ms_powerpoint ($allow_ms_powerpoint) {
        $this->allow_ms_powerpoint = $allow_ms_powerpoint;
    }

    /**
     * Get Publisher \
     * Allow the student to upload Microsoft Publisher files. \
     * @return boolean $allow_ms_publisher \
     */
    public function get_allow_ms_publisher() {
        return $this->allow_ms_publisher;
    }

    /**
     * Set Publisher \
     * Allow the student to upload Microsoft Publisher files. \
     * @param boolean $allow_ms_publisher \
     */
    public function set_allow_ms_publisher ($allow_ms_publisher) {
        $this->allow_ms_publisher = $allow_ms_publisher;
    }

    /**
     * Get Open Office \
     * Allow the student to upload Open Office file types including: odf, odt, ods, odp. \
     * @return boolean $allow_open_office \
     */
    public function get_allow_open_office() {
        return $this->allow_open_office;
    }

    /**
     * Set Open Office \
     * Allow the student to upload Open Office file types including: odf, odt, ods, odp. \
     * @param boolean $allow_open_office \
     */
    public function set_allow_open_office ($allow_open_office) {
        $this->allow_open_office = $allow_open_office;
    }

    /**
     * Get Video \
     * Allow the student to upload video file types including: mov, avi, mp4, webm, wmv. Uploaded videos will be automatically 
	 * converted to the mp4 format.<br>Note: to access and use this file type option, please contact us. \
     * @return boolean $allow_video \
     */
    public function get_allow_video() {
        return $this->allow_video;
    }

    /**
     * Set Video \
     * Allow the student to upload video file types including: mov, avi, mp4, webm, wmv. Uploaded videos will be automatically 
	 * converted to the mp4 format.<br>Note: to access and use this file type option, please contact us. \
     * @param boolean $allow_video \
     */
    public function set_allow_video ($allow_video) {
        $this->allow_video = $allow_video;
    }

    /**
     * Get MATLAB \
     * Allow the student to upload MATLAB file types including: m, fig, mat. \
     * @return boolean $allow_matlab \
     */
    public function get_allow_matlab() {
        return $this->allow_matlab;
    }

    /**
     * Set MATLAB \
     * Allow the student to upload MATLAB file types including: m, fig, mat. \
     * @param boolean $allow_matlab \
     */
    public function set_allow_matlab ($allow_matlab) {
        $this->allow_matlab = $allow_matlab;
    }

    /**
     * Get Quartus \
     * Allow the student to upload Quartus file types including: bdf, bsf. \
     * @return boolean $allow_altera_quartus \
     */
    public function get_allow_altera_quartus() {
        return $this->allow_altera_quartus;
    }

    /**
     * Set Quartus \
     * Allow the student to upload Quartus file types including: bdf, bsf. \
     * @param boolean $allow_altera_quartus \
     */
    public function set_allow_altera_quartus ($allow_altera_quartus) {
        $this->allow_altera_quartus = $allow_altera_quartus;
    }

    /**
     * Get Verilog \
     * Allow the student to upload Verilog file types including: .sv . \
     * @return boolean $allow_verilog \
     */
    public function get_allow_verilog() {
        return $this->allow_verilog;
    }

    /**
     * Set Verilog \
     * Allow the student to upload Verilog file types including: .sv . \
     * @param boolean $allow_verilog \
     */
    public function set_allow_verilog ($allow_verilog) {
        $this->allow_verilog = $allow_verilog;
    }

    /**
     * Get C \
     * Allow the student to upload .c files. \
     * @return boolean $allow_c \
     */
    public function get_allow_c() {
        return $this->allow_c;
    }

    /**
     * Set C \
     * Allow the student to upload .c files. \
     * @param boolean $allow_c \
     */
    public function set_allow_c ($allow_c) {
        $this->allow_c = $allow_c;
    }

    /**
     * Get H \
     * Allow the student to upload .h files. \
     * @return boolean $allow_h \
     */
    public function get_allow_h() {
        return $this->allow_h;
    }

    /**
     * Set H \
     * Allow the student to upload .h files. \
     * @param boolean $allow_h \
     */
    public function set_allow_h ($allow_h) {
        $this->allow_h = $allow_h;
    }

    /**
     * Get S \
     * Allow the student to upload .s files. \
     * @return boolean $allow_s \
     */
    public function get_allow_s() {
        return $this->allow_s;
    }

    /**
     * Set S \
     * Allow the student to upload .s files. \
     * @param boolean $allow_s \
     */
    public function set_allow_s ($allow_s) {
        $this->allow_s = $allow_s;
    }

    /**
     * Get V \
     * Allow the student to upload .v files. \
     * @return boolean $allow_v \
     */
    public function get_allow_v() {
        return $this->allow_v;
    }

    /**
     * Set V \
     * Allow the student to upload .v files. \
     * @param boolean $allow_v \
     */
    public function set_allow_v ($allow_v) {
        $this->allow_v = $allow_v;
    }

    /**
     * Get C++ \
     * Allow the student to upload .cpp files. \
     * @return boolean $allow_cpp \
     */
    public function get_allow_cpp() {
        return $this->allow_cpp;
    }

    /**
     * Set C++ \
     * Allow the student to upload .cpp files. \
     * @param boolean $allow_cpp \
     */
    public function set_allow_cpp ($allow_cpp) {
        $this->allow_cpp = $allow_cpp;
    }

    /**
     * Get Assembly \
     * Allow the student to upload .asm files. \
     * @return boolean $allow_assembly \
     */
    public function get_allow_assembly() {
        return $this->allow_assembly;
    }

    /**
     * Set Assembly \
     * Allow the student to upload .asm files. \
     * @param boolean $allow_assembly \
     */
    public function set_allow_assembly ($allow_assembly) {
        $this->allow_assembly = $allow_assembly;
    }

    /**
     * Get LabVIEW \
     * Allow the student to upload LabVIEW virtual instrument (.vi) files. \
     * @return boolean $allow_labview \
     */
    public function get_allow_labview() {
        return $this->allow_labview;
    }

    /**
     * Set LabVIEW \
     * Allow the student to upload LabVIEW virtual instrument (.vi) files. \
     * @param boolean $allow_labview \
     */
    public function set_allow_labview ($allow_labview) {
        $this->allow_labview = $allow_labview;
    }

    /**
     * Get Photoshop \
     * Allow the student to upload Adobe Photoshop (.psd) files. \
     * @return boolean $allow_psd \
     */
    public function get_allow_psd() {
        return $this->allow_psd;
    }

    /**
     * Set Photoshop \
     * Allow the student to upload Adobe Photoshop (.psd) files. \
     * @param boolean $allow_psd \
     */
    public function set_allow_psd ($allow_psd) {
        $this->allow_psd = $allow_psd;
    }

    /**
     * Get Illustrator \
     * Allow the student to upload Adobe Illustrator (.ai) files. \
     * @return boolean $allow_ai \
     */
    public function get_allow_ai() {
        return $this->allow_ai;
    }

    /**
     * Set Illustrator \
     * Allow the student to upload Adobe Illustrator (.ai) files. \
     * @param boolean $allow_ai \
     */
    public function set_allow_ai ($allow_ai) {
        $this->allow_ai = $allow_ai;
    }

    /**
     * Get Math renderer \
     * When a question contains math, this setting allows you to select your preferred math renderer: MathJax or MathQuill. \
     * @return string $math_renderer \
     */
    public function get_math_renderer() {
        return $this->math_renderer;
    }

    /**
     * Set Math renderer \
     * When a question contains math, this setting allows you to select your preferred math renderer: MathJax or MathQuill. \
     * @param string $math_renderer \
     */
    public function set_math_renderer ($math_renderer) {
        $this->math_renderer = $math_renderer;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

