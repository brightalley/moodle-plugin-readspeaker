<?php

class block_readspeaker_embhl_testcase extends advanced_testcase {
    public function test_get_content() {
        global $CFG;

        $this->resetAfterTest(true);
        set_config('lang', 'it_it', 'readspeaker_embhl');

        $titleText = array("it_it" => "Ascolta questa pagina con ReadSpeaker");
        $title = $titleText["it_it"];

        $listenText = array("it_it" => "Ascolta");
        $listenDesc = $listenText["it_it"];

        $isHTTPS = (!empty($_SERVER['HTTPS']) || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443));
        $protocol = $isHTTPS ? "https://" : "http://";
        $slink = "f1-";
        $region = get_config('readspeaker_embhl', 'region');

        $pageURL = $protocol.(isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '').(isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '');
        $encodedURL = urlencode($pageURL);

        $dr_path = $CFG->wwwroot . "/blocks/readspeaker_embhl/docreader/proxy.php";
        $content = new stdClass;
        $content->text = '';

        $this->page->requires->yui_module('moodle-block_readspeaker_embhl-ReadSpeaker', 'M.block_RS.ReadSpeaker.init');

        $rsdrId = get_config('readspeaker_embhl', 'docreaderenabled') ? 'cid: "' . get_config('readspeaker_embhl', 'docreaderenabled') . '"' : '';

        $content->text .= '<script type="text/javascript">window.rsConf = {general: {usePost: true}}; window.rsDocReaderConf = {'.$rsdrId.', proxypath: "'.$dr_path.'", lang: "'.get_config('readspeaker_embhl', 'lang').'"}</script>
            <div id="readspeaker_button1" class="rs_skip rsbtn rs_preserve">
            <a accesskey="L" class="rsbtn_play" title="'.$title.'" href="'.$protocol.'app-'.$region.'.readspeaker.com/cgi-bin/rsent?customerid='.get_config('readspeaker_embhl', 'cid').'&amp;lang='.get_config('readspeaker_embhl', 'lang').'&amp;readid='.get_config('readspeaker_embhl', 'readid').'&amp;url='.$encodedURL.get_config('readspeaker_embhl', 'customparams').'">
            <span class="rsbtn_left rsimg rspart"><span class="rsbtn_text"><span>'.$listenDesc.'</span></span></span>
            <span class="rsbtn_right rsimg rsplay rspart"></span>
            </a>
            </div>';

        $plugin_config_language = get_config('readspeaker_embhl', 'lang');
        $plugin_config_customerid = get_config('readspeaker_embhl', 'cid');
        $plugin_config_readid = get_config('readspeaker_embhl', 'readid');

        $plugin_config_docreader = get_config('readspeaker_embhl', 'docreaderenabled');
        $plugin_config_region = get_config('readspeaker_embhl', 'region');

        $plugin_custom_javascriptparams = get_config('readspeaker_embhl', 'customjavascript');
        $plugin_custom_params = get_config('readspeaker_embhl', 'customparams');

        $content->text .= '<script type="text/javascript">window.rsConf = {general: {usePost: true}, moodle: {customerid: "'.$plugin_config_customerid.'", region: "'. $region .'"}, ui: {tools: {voicesettings: true}}}; window.rsDocReaderConf = {'.$rsdrId.'proxypath: "'.$dr_path.'", lang: "'.$plugin_config_language.'"}</script>'.
        '<div id="readspeaker_button1" class="rs_skip rsbtn rs_preserve rscompact">
            <a accesskey="L" class="rsbtn_play" title="'.$title.'" href="//app-'.$region.'.readspeaker.com/cgi-bin/rsent?customerid='.$plugin_config_customerid.'&amp;lang='.$plugin_config_language.'&amp;readid='.$plugin_config_readid.$audiofilename.'&amp;url='.$encodedURL.$plugin_custom_params.'">
            <span class="rsbtn_left rsimg rspart"><span class="rsbtn_text"><span>'.$listenDesc.'</span></span></span>
            <span class="rsbtn_right rsimg rsplay rspart"></span>
            </a>
            </div>';

        $testMock = $this->createMock('block_readspeaker_embhl');
        $testMock->expects($this->any())->method('get_content')->will($this->returnValue($content));
    }
}
