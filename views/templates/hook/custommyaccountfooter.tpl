<!-- Custom My account footer block -->
<div class="custommyaccount">
    <h4 class="title_block"><a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Manage my customer account' mod='blockmyaccountfooter'}" rel="nofollow">{l s='My account' mod='blockmyaccountfooter'}</a></h4>
    <div class="block_content">
        <ul class="bullet">
            {foreach $links as $link}
                <li><a href="{$link->getLink()}">{$link->title[$lang_id]}</a></li>
            {/foreach}
        </ul>
    </div>
</div>
<!-- /Custom My account footer block -->