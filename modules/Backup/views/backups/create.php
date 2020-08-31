<div>
    <ul class="uk-breadcrumb">
        <li><a href="@route('/backup')">@lang('Backup')</a></li>
        <li class="uk-active"><span>@lang('Create')</span></li>
    </ul>
</div>

<div class="uk-margin-top" riot-view>
    <div class="uk-width-medium-1-3 uk-viewport-height-1-2 uk-container-center uk-text-center uk-flex uk-flex-center uk-flex-middle"
            if="{ loading }">
            <div class="uk-animation-fade uk-text-center">
                <cp-preloader class="uk-container-center"></cp-preloader>
            </div>
        </div>
    <form id="account-form" class="uk-form uk-grid uk-grid-gutter" onsubmit="{ submit }" show="{ !loading  }">

        <h3>@lang('Create new backup')</h3>
        <div class="uk-width-medium-1-1">

            <div class="uk-form-row">
              <label class="uk-text-small">@lang('Description')</label>
              <input class="uk-width-1-1 uk-form-large" type="text" bind="description" autocomplete="off" required>
            </div>

            <h3>@lang('Backup Options')</h3>
            <div ref="container" class="uk-form-row" each="{option, index in options}" onclick="{ setOption }" style="cursor:pointer;">
                <div if="{!option.disabled}">
                    <div class="uk-form-switch">
                        <input ref="check" type="checkbox" id="{ option.key }" checked={option.value} />
                        <label for="{ option.key }"></label>
                    </div>
                    <span>{ option.label }</span>
                </div>
            </div>
        </div>

        <div class="uk-width-medium-1-2">
            <button class="uk-button uk-button-large uk-width-1-3 uk-button-primary uk-margin-right">@lang('Create')</button>
            <a href="@route('/backup')">@lang('Cancel')</a>
        </div>

    </form>


    <script type="view/script">

        var $this = this, $root = App.$(this.root);

        this.mixin(RiotBindMixin);
        this.loading = false;
        this.description = "@lang('Manual backup created on') " + App.Utils.dateformat(new Date(), 'MMM DD, YYYY HH:mm');
        this.definitions = {{ json_encode($definitions) }};

        this.options = [
          {'key': 'config', 'label': 'Global cockpit configuration', 'value': true, 'disabled': false},
          {'key': 'collections', 'label': 'Collections definitions', 'value': true, 'disabled': false},
          {'key': 'singletons', 'label': 'Singletons definitions', 'value': true, 'disabled': false},
          {'key': 'forms', 'label': 'Forms definitions', 'value': true, 'disabled': false},
          {'key': 'accounts', 'label': 'User accounts', 'value': true, 'disabled': false},
          {'key': 'webhooks', 'label': 'Webhooks definitions', 'value': true, 'disabled': false},
          {'key': 'entries', 'label': 'Include collection entries', 'value': true, 'disabled': false},
          {'key': 'assets', 'label': 'Assets', 'value': true, 'disabled': false},
          {'key': 'uploads', 'label': 'File uploads', 'value': true, 'disabled': false},
          {'key': 'regions', 'label': 'Regions (deprecated, ensure you have the legacy regions addon installed', 'value': this.definitions.includes('regions'), 'disabled': true}
        ];

        this.on('mount', function(){
            // bind clobal command + save
            Mousetrap.bindGlobal(['command+s', 'ctrl+s'], function(e) {
                e.preventDefault();
                $this.submit();
                return false;
            });

            $this.update();
        });

        submit(e) {
            if(e) e.preventDefault();
            this.loading = true;
            this.update();
            App.request("/backup/save", {"description": this.description, "options": this.options}).then(function(data){
                $this.backup = data;
                App.ui.notify("Backup created", "success");
                setTimeout(function() {
                    this.loading = false;
                  location.href = App.route('/backup');
                }, 1000)
            },function(err){
                this.loading = false;
                App.ui.notify("Something went wrong.", "danger");
            });

            return false;
        }

        setOption(e) {
            e.preventDefault();
            const idx = e.item.index;
            if (this.options[idx].key == 'entries' && !this.options[1].value) {
                return false;
            }
            if (this.options[idx].key == 'assets' && !this.options[8].value) {
                return false;
            }
            this.options[idx].value = !this.options[idx].value;
            if (this.options[idx].key == 'uploads' && !this.options[idx].value) {
                this.options[7].value = false;
            }
            if (this.options[idx].key == 'collections' && !this.options[idx].value) {
                this.options[6].value = false;
            }
        }

    </script>

</div>
