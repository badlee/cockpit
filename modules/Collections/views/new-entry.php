<script type="riot/tag" src="@base('collections:assets/collection-entrypreview.tag')?nc={{ $app['debug'] ? time() : $app['cockpit/version'] }}"></script>
<script type="riot/tag" src="@base('collections:assets/collection-linked.tag')?nc={{ $app['debug'] ? time() : $app['cockpit/version'] }}"></script>
<script type="riot/tag" src="@base('collections:assets/entries-table.tag')?nc={{ $app['debug'] ? time() : $app['cockpit/version'] }}"></script>

<style>
    @if(isset($collection['color']) && $collection['color']) 
    .app-header {
        border-top: 8px <?=$collection['color']?> solid;
    }
    @endif
    .uk-tab-entry{
        display: flex;
        overflow-x: auto;
    }
    .uk-tab-entry>li{
        min-width: 210px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .uk-tab-entry>li>a, .uk-tab-content .uk-tab-content-title .uk-tab-content-title-label{
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .tab-entry-line > a[class*='uk-icon-']{
        padding-left : 10px;
        padding-right : 10px;
    }
    .uk-tab-entry::-webkit-scrollbar {    display: none;}
    .uk-tab-content{
        margin: 0px !important;
    }
    .uk-tab-content .desc{
        background: #f0f8ff;
        padding: 20px;
        margin: 10px 0;
        border-radius: 5px;
    }
    .uk-scrollable-box {
        border: none;
        padding-top: 0;
        padding-left: 0;
    }

    .collection-grid-avatar-container {
        border-top: 1px rgba(0,0,0,0.1) solid;
    }

    .collection-grid-avatar {
        transform: translateY(-50%);
        max-width: 40px;
        max-height: 40px;
        border: 1px #fff solid;
        box-shadow: 0 0 40px rgba(0,0,0,0.3);
        border-radius: 50%;
        margin: 0 auto;
        overflow: hidden;
    }

    .collection-grid-avatar .uk-icon-spinner {
        display: none;
    }
    .item-grid-avatar .uk-text-small {
        font-size: 14px;
        line-height: 16px;
    }
</style>

<script>
    window.__collectionEntry = <?=json_encode($entry)?>;
    window.__collection = <?=json_encode($collection)?>;
    function CollectionHasFieldAccess (field)  {
        var acl = field.acl || [];

        if (field.name == '_modified' ||
            App.$data.user.group == 'admin' ||
            !acl ||
            (Array.isArray(acl) && !acl.length) ||
            acl.indexOf(App.$data.user.group) > -1 ||
            acl.indexOf(App.$data.user._id) > -1
        ) { return true; }
        return false;
    }
</script>

<div riot-view>

    <div class="header-sub-panel">

        <div class="uk-container uk-container-center">

            <ul class="uk-breadcrumb">
                <li><a href="@route('/collections')">@lang('Collections')</a></li>
                <li data-uk-dropdown="mode:'hover', delay:300">
                    <a href="@route('/collections/entries/'.$collection['name'])"><i class="uk-icon-bars"></i> {{ htmlspecialchars(@$collection['label'] ? $collection['label']:$collection['name'], ENT_QUOTES, 'UTF-8') }}</a>

                    @if($app->module('collections')->hasaccess($collection['name'], 'collection_edit'))
                    <div class="uk-dropdown">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li class="uk-nav-header">@lang('Actions')</li>
                            <li><a href="@route('/collections/collection/'.$collection['name'])">@lang('Edit')</a></li>
                            @if($app->module('collections')->hasaccess($collection['name'], 'entries_delete'))
                            <li class="uk-nav-divider"></li>
                            <li><a href="@route('/collections/trash/collection/'.$collection['name'])">@lang('Trash')</a></li>
                            @endif
                            <li class="uk-nav-divider"></li>
                            <li class="uk-text-truncate"><a href="@route('/collections/export/'.$collection['name'])" download="{{ $collection['name'] }}.collection.json">@lang('Export entries')</a></li>
                            <li class="uk-text-truncate"><a href="@route('/collections/import/collection/'.$collection['name'])">@lang('Import entries')</a></li>
                        </ul>
                    </div>
                    @endif
                </li>
            </ul>

            
            @if($isEdit && count($linked))
            <div class="uk-flex uk-flex-middle tab-entry-line">
                <a class="uk-text-bold uk-link-muted uk-icon-chevron-left" if="{isOverflowing}" onclick="{scrollTabLeft}"></a>
                <ul class="uk-tab uk-tab-entry" id="tab-entry">
                    <li style="min-width : 100px" link="my-entry" onclick="{openTab}" class="uk-active"><a class="uk-text-bold uk-link-muted">
                        <div class="uk-flex uk-flex-middle uk-text-bold">
                            <div class="uk-margin-small-right">
                                <img src="@url($collection['icon'] ? 'assets:app/media/icons/'.$collection['icon']:'collections:icon.svg')" width="30" alt="icon">
                                <span>{App.i18n.get(entry._id ? '{{$canEdit ? 'Edit Entry' : 'View'}}':'Add Entry')}</span>
                            </div>
                        </div>
                    </a></li>
                    @foreach($linked as $name=>$meta)
                    <li style="min-width : 100px" link="{{ $name }}" onclick="{openTab}"><a class="uk-text-bold uk-link-muted">
                        <div class="uk-flex uk-flex-middle uk-text-bold">
                            <div class="uk-margin-small-right">
                                <img src="{{$meta['icon']}}" width="30" alt="icon">
                                <span>{{$meta["label"]}}</span>
                            </div>
                        </div>
                    </a></li>
                    @endforeach
                </ul>
                <a class="uk-text-bold uk-link-muted uk-icon-chevron-right" if="{isOverflowing}" onclick="{scrollTabRight}" ></a>
            </div>
            @else
            <div class="uk-flex uk-flex-middle uk-text-bold uk-h3">
                <div class="uk-margin-small-right">
                    <img src="@url($collection['icon'] ? 'assets:app/media/icons/'.$collection['icon']:'collections:icon.svg')" width="40" alt="icon">
                </div>
                <div class="uk-margin-right">{App.i18n.get(entry._id ? '{{$canEdit ? 'Edit Entry' : 'View'}}':'Add Entry')}</div>
            </div>
            @endif
    </div>
    <div class="uk-container uk-container-center uk-tab-main-contents" if="{ !fields.length }">
        <br/>
        <div class="uk-alert">
            @lang('No fields defined'). <a href="@route('/collections/collection')/{ collection.name }">@lang('Define collection fields').</a>
        </div>
    </div>
    <div class="uk-container uk-container-center uk-tab-main-contents">
        @foreach($linked as $name=>$meta)
        <div class="uk-grid uk-tab-content" show="{viewTab == '{{$name}}'}" >
            <div class="uk-width-1-1 uk-flex uk-flex-middle uk-margin-top uk-text-bold uk-h3 uk-tab-content-title">
                <div class="uk-margin-small-right">
                @if(count($meta["@links"]) > 1)
                    <img class="uk-svg-adjust" src="@url('collections:icon.svg')" width="20" alt="icon" data-uk-svg>
                    <span  data-uk-dropdown="mode:'hover', delay:30" class="uk-margin-right uk-text-bold uk-link-muted uk-tab-content-title-label">
                        { linked.{{$name}}.selectedLink.label }<i class=" uk-margin-small-left uk-icon-chevron-down"></i>
                        <div class="uk-dropdown uk-dropdown-close">
                            <ul class="uk-nav uk-nav-dropdown">
                            @foreach($meta["@links"] as $_name=>$_link)
                                <li link="{{$name}}.{{$_name}}" ><a onclick="{selectLink}">{{$_link["label"]}}</a></li>
                            @endforeach
                            </ul>
                        </div>
                    </span>
                @else
                    <img class="uk-svg-adjust" src="@url('collections:icon.svg')" width="20" alt="icon" data-uk-svg>
                    <span class="uk-margin-right uk-text-bold uk-link-muted uk-tab-content-title-label">
                        { linked.{{$name}}.selectedLink.label }
                    </span>
                @endif
                </div>
                <div class="uk-flex-item-1"></div>
                @trigger('collections.entry.link.top', [$name,$meta])
            </div>
            <div class="uk-grid-margin uk-width-1-1">
            @foreach($meta["@links"] as $_name=>$_link)
                <entries-table show="{ linked.{{$name}}.selectedLink.name == '{{$_name}}' }" entry="{entry}" name="{{$name}}" collection="{ linked.{{$name}} }" selectedLink="{ linked.{{$name}}['@links'].{{$_name}} }"></entries-table>
            @endforeach
            </div>
        </div>
        @endforeach

        <div help="Editeur" class="uk-grid uk-tab-content" show="{viewTab == 'my-entry'}">
            @if($canEdit)
            <div class="uk-grid-margin uk-width-medium-3-4 uk-width-large-4-5">
                <form class="uk-form" if="{ fields.length }" onsubmit="{ submit }">

                    <div class="uk-grid uk-grid-match uk-grid-gutter" if="{ !preview }">

                        <div class="uk-width-medium-{field.width}" each="{field,idx in fields}" show="{checkVisibilityRule(field) && (!group || (group == field.group)) }" if="{ hasFieldAccess(field.name) }" no-reorder>

                            <cp-fieldcontainer>

                                <label title="{ field.name }">

                                    <span class="uk-text-bold"><i class="uk-icon-pencil-square uk-margin-small-right"></i> { field.label || App.Utils.ucfirst(field.name) }</span>
                                    <span class="uk-text-muted" show="{field.required}">&mdash; @lang('required')</span>

                                    <span if="{ field.localize }" data-uk-dropdown="mode:'click'">
                                        <a class="uk-icon-globe" title="@lang('Localized field')" data-uk-tooltip="pos:'right'"></a>
                                        <div class="uk-dropdown uk-dropdown-close">
                                            <ul class="uk-nav uk-nav-dropdown">
                                                <li class="uk-nav-header">@lang('Copy content from:')</li>
                                                <li show="{parent.lang}"><a onclick="{parent.copyLocalizedValue}" lang="" field="{field.name}">{App.$data.languageDefaultLabel}</a></li>
                                                <li show="{parent.lang != language.code}" each="{language,idx in languages}" value="{language.code}"><a onclick="{parent.parent.copyLocalizedValue}" lang="{language.code}" field="{field.name}">{language.label}</a></li>
                                            </ul>
                                        </div>
                                    </span>

                                </label>

                                <div class="uk-margin-top">
                                    <cp-field type="{field.type || 'text'}" bind="entry.{ field.localize && parent.lang ? (field.name+'_'+parent.lang):field.name }" opts="{ field.options || {} }"></cp-field>
                                </div>

                                <div class="uk-margin-top uk-text-small uk-text-muted" if="{field.info}">
                                    { field.info || ' ' }
                                </div>

                            </cp-fieldcontainer>

                        </div>

                    </div>

                    <cp-actionbar>
                        <div class="uk-container uk-container-center">
                            <button class="uk-button uk-button-large uk-button-primary">@lang('Save')</button>
                            <a class="uk-button uk-button-link" onclick="history.back()">
                                <span show="{ !entry._id }">@lang('Cancel')</span>
                                <span show="{ entry._id }">@lang('Close')</span>
                            </a>
                        </div>
                    </cp-actionbar>

                </form>

            </div>
            <div class="uk-width-medium-1-4  uk-width-large-1-5 uk-flex-order-first uk-flex-order-last-medium">
                <div class="uk-margin" if="{entry._id}">
                    <div class="uk-margin-small-top">
                        <div class="uk-button-group">
                            <a class="uk-button" onclick="{showPreview}" if="{ collection.contentpreview && collection.contentpreview.enabled }">@lang('Preview')</a>
                            @if($app->module('cockpit')->isSuperAdmin())
                            <a class="uk-button" onclick="{showEntryObject}">@lang('Json')</a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="uk-panel uk-panel-framed uk-width-1-1 uk-form-select uk-form" if="{ languages.length }">

                    <div class="uk-text-bold {lang ? 'uk-text-primary' : 'uk-text-muted'}">
                        <i class="uk-icon-globe"></i>
                        <span class="uk-margin-small-left">{ lang ? _.find(languages,{code:lang}).label:App.$data.languageDefaultLabel }</span>
                    </div>

                    <select bind="lang" onchange="{persistLanguage}">
                        <option value="">{App.$data.languageDefaultLabel}</option>
                        <option each="{language,idx in languages}" value="{language.code}">{language.label}</option>
                    </select>
                </div>

                <div class="uk-margin" if="{ fields.length }">
                    <label class="uk-text-small">@lang('Last Modified')</label>
                    <div class="uk-margin-small-top uk-text-muted" if="{entry._id}">
                        <i class="uk-icon-calendar uk-margin-small-right"></i> { App.Utils.dateformat( new Date( 1000 * entry._modified )) }
                    </div>
                    <div class="uk-margin-small-top uk-text-muted" if="{!entry._id}">@lang('Not saved yet')</div>
                </div>

                <div class="uk-margin" if="{entry._id}">
                    <label class="uk-text-small">@lang('Revisions')</label>
                    <div class="uk-margin-small-top">
                        <span class="uk-position-relative">
                            <cp-revisions-info class="uk-badge uk-text-large" rid="{entry._id}"></cp-revisions-info>
                            <a class="uk-position-cover" href="@route('/collections/revisions/'.$collection['name'])/{entry._id}"></a>
                        </span>
                    </div>
                </div>

                <div class="uk-margin" if="{entry._id && entry._mby}">
                    <label class="uk-text-small">@lang('Last update by')</label>
                    <div class="uk-margin-small-top">
                        <cp-account account="{entry._mby}"></cp-account>
                    </div>
                </div>
                <div class="uk-panel uk-panel-framed uk-width-1-1" if="{ App.Utils.count(_groups) > 1}">
                    <ul class="uk-tab header-sub-panel-tab uk-flex uk-flex-center" divider="true" if="{App.Utils.count(_groups) < 6}">
                        <li class="{ !group && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get('All') }</a></li>
                        <li class="{ group==parent.group && 'uk-active'}" each="{group, idx in _groups}" show="{ parent.groups[group].length }"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get(group) }</a></li>
                    </ul>
                    <ul class="uk-tab header-sub-panel-tab uk-flex uk-flex-center" divider="true" if="{ App.Utils.count(_groups) > 5 }">
                        <li class="uk-active" data-uk-dropdown="mode:'click', pos:'bottom-center'">
                            <a>{ App.i18n.get(group || 'All') } <i class="uk-margin-small-left uk-icon-angle-down"></i></a>
                            <div class="uk-dropdown uk-dropdown-scrollable uk-dropdown-close">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li class="uk-nav-header">@lang('Groups')</li>
                                    <li class="{ !group && 'uk-active'}"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get('All') }</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li class="{ group==parent.group && 'uk-active'}" each="{group in _groups}" show="{ parent.groups[group].length }"><a class="uk-text-capitalize" onclick="{ toggleGroup }">{ App.i18n.get(group) }</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                @trigger('collections.entry.aside', [$collection['name'], $collection['name']])

            </div>
            @else
            <div class="uk-grid uk-grid-match uk-grid-width-1-1 uk-flex-center item-grid-avatar" style="align-items: center;align-content: center;text-align: center;margin: 20px auto;">
                <div class="uk-panel uk-panel-box uk-panel-card uk-panel-card-hover">

                    <div class="uk-position-relative uk-nbfc">
                        <canvas width="400" height="250"></canvas>
                        <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle">

                        <cp-thumbnail src="{ isImageField(entry) }" width="400" height="250" if="{ isImageField(entry) }">
                        </cp-thumbnail>

                        <div class="uk-svg-adjust uk-text-primary" style="color:{ collection['color'] } !important;"
                            if="{ !isImageField(entry) }">
                            <img src="@url($collection['icon'] ? 'assets:app/media/icons/'.$collection['icon']:'collections:icon.svg')" width="80" alt="icon" data-uk-svg>
                        </div>
                        </div>
                    </div>
                    <div class="collection-grid-avatar-container">
                        <div class="collection-grid-avatar">
                        <cp-account account="{entry._mby || entry._by}" label="{false}" size="40" if="{entry._mby || entry._by}">
                        </cp-account>
                        <cp-gravatar alt="?" size="40" if="{!(entry._mby || entry._by)}"></cp-gravatar>
                        </div>
                    </div>
                    <div class="uk-flex uk-flex-middle uk-margin-small-top">

                        <div class="uk-flex-item-1 uk-margin-small-right uk-text-small">
                        <span class="uk-text-success uk-margin-small-right">{ App.Utils.dateformat( new Date(
                            1000 * entry._created )) }</span>
                        <span class="uk-text-primary">{ App.Utils.dateformat( new Date( 1000 * entry._modified
                            )) }</span>
                        </div>
                    </div>

                    <div class="uk-margin-top uk-scrollable-box">
                        <div class="uk-margin-small-bottom" each="{field,idy in fields}"
                        if="{ field.name != '_modified' && field.name != '_created' }">
                        <span class="uk-text-small uk-text-uppercase uk-text-muted">{ field.label || field.name
                            }</span>
                        <a class="uk-link-muted uk-text-small uk-display-block uk-text-truncate">
                            <raw content="{ App.Utils.renderValue(field.type, entry[field.name], field) }"
                            if="{entry[field.name] !== undefined}"></raw>
                            <span class="uk-icon-eye-slash uk-text-muted" if="{entry[field.name] === undefined}"></span>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <cp-actionbar>
                <div class="uk-container uk-container-center">
                    <a class="uk-button uk-button-large uk-button-primary" onclick="history.back()">
                        <span show="{ entry._id }">@lang('Close')</span>
                    </a>
                </div>
            </cp-actionbar>
            @endif
        </div>
    </div>

    <collection-entrypreview collection="{collection}" entry="{entry}" groups="{ groups }" fields="{ fields }" fieldsidx="{ fieldsidx }" excludeFields="{ excludeFields }" languages="{ languages }" lang="{ lang }" settings="{ collection.contentpreview }" if="{ preview }"></collection-entrypreview>
    <cp-inspectobject ref="inspect"></cp-inspectobject>

    <script type="view/script">

        var $this = this;

        this.mixin(RiotBindMixin);

        this.linked = {{ json_encode($linked) }};
        this.collection   = window.__collection;
        this.fields       = this.collection.fields;
        this.fieldsidx    = {};
        this.excludeFields = {{ json_encode($excludeFields) }};

        this.entry        = window.__collectionEntry;
        this.viewTab      = 'my-entry';

        this.languages    = App.$data.languages;
        this.groups       = {Main:[]};
        this.group        = '';
        this.canEdit      = {{json_encode($canEdit)}};
        this.isOverflowing = false;
        window.entry = this;
        $this.imageField = null;
        if (this.languages.length) {
            this.lang = App.session.get('collections.entry.'+this.collection._id+'.lang', '');
        }

        // fill with default values
        this.fields.forEach(function(field) {

            $this.fieldsidx[field.name] = field;
            if (!$this.imageField && (field.type == 'image' || field.type == 'asset')) {
                $this.imageField = field;
            }
            if ($this.entry[field.name] === undefined) {
                $this.entry[field.name] = field.options && field.options.default || null;
            }

            if (field.localize && $this.languages.length) {

                $this.languages.forEach(function(lang) {

                    var key = field.name+'_'+lang.code;

                    if ($this.entry[key] === undefined) {

                        if (field.options && field.options['default_'+lang.code] === null) {
                            return;
                        }

                        $this.entry[key] = field.options && field.options.default || null;
                        $this.entry[key] = field.options && field.options['default_'+lang.code] || $this.entry[key];
                    }
                });
            }

            if (field.type == 'password') {
                $this.entry[field.name] = '';
            }

            if ($this.excludeFields.indexOf(field.name) > -1) {
                return;
            }

            if (field.group && !$this.groups[field.group]) {
                $this.groups[field.group] = [];
            } else if (!field.group) {
                field.group = 'Main';
            }

            $this.groups[field.group || 'Main'].push(field);
        });

        this._groups = Object.keys(this.groups).sort(function (a, b) {
            return a.toLowerCase().localeCompare(b.toLowerCase());
        });

        this.on('mount', function(){
            if(!('isOverflowing' in Element.prototype))
                Element.prototype.isOverflowing = function(){
                    return this.scrollHeight > this.clientHeight || this.scrollWidth > this.clientWidth;
                }
            if(this.canEdit){
                // bind global command + save
                Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                    if ($this.viewTab != 'my-entry' || App.$('.uk-modal.uk-open').length) {
                        return false;
                    }

                    $this.submit(e);
                    return false;
                });

                // wysiwyg cmd + save hack
                App.$(this.root).on('submit', function(e, component) {
                    if (component) $this.submit(e);
                });
            }
            @if($isEdit && count($linked))
            window.isOverflowing = this.isOverflowing = App.$("#tab-entry").get(0).isOverflowing();
            if(this.isOverflowing)
                setTimeout(()=>{
                    this.update()
                },300);
            App.$(window).on('resize', ()=>{
                var isOverflowing = this.isOverflowing;
                this.isOverflowing = App.$("#tab-entry").get(0).isOverflowing();
                if(isOverflowing != this.isOverflowing){
                    this.update();
                }
            });
            @endif
            // lock resource
            var idle = setInterval(function() {

                if (!($this.entry._id && $this.canEdit)) return;

                Cockpit.lockResource($this.entry._id, function(e){
                    window.location.href = App.route('/collections/entry/'+$this.collection.name+'/'+$this.entry._id);
                });

                clearInterval(idle);

            }, 60000);

        });
        openTab(e){
            var active = "uk-active";
            var me = e.target.closest("li");
            App.$(me).parent().find("li").removeClass(active);
            App.$(me).addClass(active);
            if(this.isOverflowing)
                me.scrollIntoView(true);
            this.viewTab = App.$(me).attr("link");
        }
        selectLink(e){
            var me = e.target.closest("li");
            var link = App.$(me).attr("link").split(".");
            this.linked[link[0]].selectedLink = {
                name : this.linked[link[0]]["@links"][link[1]].name,
                label : this.linked[link[0]]["@links"][link[1]].label,
            };
        }
        scrollTabRight(){
            var el = App.$("#tab-entry");
            el.scrollLeft(el.scrollLeft() + 100)
        }

        scrollTabLeft(){
            var el = App.$("#tab-entry");
            el.scrollLeft(el.scrollLeft() - 100)
        }
        toggleGroup(e) {
            this.group = e.item && e.item.group || false;
        }

        submit(e) {

            if (e) {
                e.preventDefault();
            }

            var required = [], val;

            this.fields.forEach(function(field) {

                val = $this.entry[field.name];

                if (field.required && (!val || (Array.isArray(val) && !val.length))) {

                    if (!(val===false || val===0)) {
                        required.push(field.label || field.name);
                    }
                }
            });

            if (required.length) {
                App.ui.notify([
                    App.i18n.get('Fill in these required fields before saving:'),
                    '<div class="uk-margin-small-top">'+required.join(',')+'</div>'
                ].join(''), 'danger');
                return;
            }

            App.request('/collections/save_entry/'+this.collection.name, {entry:this.entry}).then(function(entry) {

                if (entry) {

                    App.ui.notify("Saving successful", "success");

                    _.extend($this.entry, entry);

                    $this.fields.forEach(function(field){

                        if (field.type == 'password') {
                            $this.entry[field.name] = '';
                        }
                    });

                    if ($this.tags['cp-revisions-info']) {
                        $this.tags['cp-revisions-info'].sync();
                    }

                    $this.update();

                } else {
                    App.ui.notify("Saving failed.", "danger");
                }
            }, function(res) {
                App.ui.notify(res && (res.message || res.error) ? (res.message || res.error) : 'Saving failed.', 'danger');
            });

            return false;
        }

        showPreview() {
            this.preview = true;
        }

        hasFieldAccess(field) {

            var acl = this.fieldsidx[field] && this.fieldsidx[field].acl || [];

            if (this.excludeFields.indexOf(field) > -1) {
                return false;
            }

            if (field == '_modified' ||
                App.$data.user.group == 'admin' ||
                !acl ||
                (Array.isArray(acl) && !acl.length) ||
                acl.indexOf(App.$data.user.group) > -1 ||
                acl.indexOf(App.$data.user._id) > -1
            ) {
                return true;
            }

            return false;
        }

        persistLanguage(e) {
            App.session.set('collections.entry.'+this.collection._id+'.lang', e.target.value);
        }

        copyLocalizedValue(e) {

            var field = e.target.getAttribute('field'),
                lang = e.target.getAttribute('lang'),
                val = JSON.stringify(this.entry[field+(lang ? '_':'')+lang]);

            this.entry[field+(this.lang ? '_':'')+this.lang] = JSON.parse(val);
        }

        checkVisibilityRule(field) {

            if (field.options && field.options['@visibility']) {

                try {
                    return (new Function('$', 'v','return ('+field.options['@visibility']+')'))(this.entry, function(key) {
                        var f = this.fieldsidx[key] || {};
                        return this.entry[(f.localize && this.lang ? (f.name+'_'+this.lang):f.name)];
                    }.bind(this));
                } catch(e) {
                    return false;
                }

                return this.data.check;
            }

            return true;
        }

        showEntryObject() {
            $this.refs.inspect.show($this.entry);
            $this.update();
        }

        showLinkedOverview() {

            console.log(this.refs)

            $this.refs.entrylinked.show($this.entry);
            $this.update();
            
        }

        isImageField(entry) {
            if (!this.imageField) {
                return false;
            }
            var data = entry[this.imageField.name];
            if (!data) {
                return false;
            }
            switch (this.imageField.type) {
                case 'asset':
                    if (data.mime && data.mime.match(/^image\//)) {
                        return ASSETS_URL + data.path;
                    }
                    break;
                case 'image':
                    if (data.path) {
                        return data.path.match(/^(http\:|https\:|\/\/)/) ? data.path : SITE_URL + '/' + data.path;
                    }
                    break;
            }
            return false;
        }

    </script>

</div>