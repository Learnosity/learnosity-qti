<div class="row">
    <div class="col-md-12">
        <select id="to-qti-fileSelect" class="file-select-row">
            <?php
            foreach ($jsonSampleFileList as $key => $value) {
                echo '<option value="' . $value . '">' . $key . '</option>';
            }
            ?>
        </select>
        <button id="to-qti-loadFile" type="button" class="btn btn-primary">Load</button>
        <button id="to-qti-submit" type="button" class="btn btn-primary">Parse</button>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p><span class="label label-default">Learnosity JSON (Question or Item)</span></p>
        <div id="to-qti-json-editor" class="lrn-json" name="jsons-editor"></div>
    </div>
    <div class="col-md-6">
        <p><span class="label label-default">QTI</span></p>
        <div id="to-qti-xml-editor" class="qti-xml" name="xml-editor"></div>
    </div>
</div>
<div class="row output-json-row">
    <div class="col-md-12">
        <p><span class="label label-default">Exceptions and All Things Ignored</span></p>
        <pre class="clipboard"><code id="to-qti-errors" class="html"></code></pre>
    </div>
</div>

<script>
    var xmlEditor = ace.edit("to-qti-xml-editor");
    xmlEditor.getSession().setMode("ace/mode/xml");
    xmlEditor.getSession().setUseWrapMode(true);
    xmlEditor.setShowPrintMargin(false);
    xmlEditor.setReadOnly(true);

    var jsonEditor = ace.edit("to-qti-json-editor");
    jsonEditor.getSession().setMode("ace/mode/json");
    jsonEditor.getSession().setUseWrapMode(true);
    jsonEditor.setShowPrintMargin(false);
</script>
<script>
    $(function () {
        $('#to-qti-submit').click(function () {
            var requestJson = jsonEditor.getValue();
            $.ajax({
                type: "POST",
                url: '?operation=toqti',
                cache: false,
                data: requestJson,
                success: function (data) {
                    var result = JSON.parse(data);
                    xmlEditor.setValue(result.xmlString);
                    $('#to-qti-errors').html(JSON.stringify(result.messages, null, 4));
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });

        $('#to-qti-loadFile').click(function () {
            $.ajax({
                type: "POST",
                data: 'filePath=' + $('#to-qti-fileSelect').val(),
                success: function (data) {
                    jsonEditor.setValue(data);
                }
            });
        });
    });
</script>
