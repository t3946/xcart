{capture name=dialog}
            <div class="jcarousel-wrapper">
                <div class="jcarousel" id="jcarousel_{$section_name}">
                    <div class="loading">Loading carousel items...</div>
                </div>

                <a href="#" class="jcarousel-control-prev" id="jcarousel-control-prev_{$section_name}">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next" id="jcarousel-control-next_{$section_name}">&rsaquo;</a>
            </div>
{/capture}
{include file="dialog.tpl" title=$section_title content=$smarty.capture.dialog extra='width="100%" class="recommends no_padding_bottom"'}
