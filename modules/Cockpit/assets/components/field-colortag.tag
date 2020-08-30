<field-colortag>


    <div class="uk-display-inline-block" data-uk-dropdown="pos:'right-center'">
        <a riot-style="font-size:{size};color:{value || '#ccc'}"><i class="uk-icon-circle"></i></a>

        <div class="uk-dropdown uk-text-center">

            <strong class="uk-text-small">{ App.i18n.get('Choose') }</strong>

            <div class="uk-grid uk-grid-small uk-margin-small-top uk-grid-width-1-4">
                <div class="uk-grid-margin" each="{color in colors}">
                    <a onclick="{parent.select}" riot-style="color:{color};"><i class="uk-icon-circle"></i></a>
                </div>
            </div>

            <div class="uk-margin-top uk-text-small">
                <a onclick="{reset}">{ App.i18n.get('Reset') }</a>
            </div>

        </div>
    </div>


    <script>

        var _defColors = [
            '#f44336','#e81e63','#9c27b0','#673ab7','#3f51b5','#2196f3','#03a9f4','#00bcd4','#009688','#4caf50','#8bc34a','#cddc39','#ffeb3b','#ffc107','#ff9800','#ff5722','#795548','#9e9e9e','#607d8b'
        ];

        this.value  = '';

        this.on('mount',function(){
            this.update();
        });

        this.on('update', function(){
            this.size   = opts.size || 'inherit';
            this.colors = opts.colors || _defColors;
        });

        this.$updateValue = function(value, field) {

            if (this.value !== value) {
                this.value = value;
                this.update();
            }

        }.bind(this);

        select(e) {
            this.$setValue(e.item.color);
        }

        reset() {
            this.$setValue('');
        }

    </script>

</field-colortag>
