import { IconStretch } from '@codexteam/icons';

export default class CustomShortcodeTool {
    constructor({data, config, api, block}) {
        this.data = data;
        this.config = config;
        this.api = api;
        this.block = block;

        this.wrapper = undefined;
        this.tunes = [
          {
            name: 'stretched',
            label: 'Stretch Image',
            icon: IconStretch,
          }
        ];

        this.CSS = {
          shortCodeHolder: 'cdx-custom-shorcode-tool',
        };
    }
    
    renderSettings() {
      return this.tunes.map(tune => ({
        ...tune,
        label: this.api.i18n.t(tune.label),
        toggle: true,
        onActivate: () => this._toggleTune(tune.name),
        isActive: !!this.data[tune.name],
      }))
    };

    _toggleTune(tune) {
      this.data[tune] = !this.data[tune];
      this._acceptTuneView();
    }

    static get toolbox() {
        return {
            title: 'Shortcode',
            icon: '<svg>...</svg>' // カスタムアイコンを設定できます
        };
    }
    
    _acceptTuneView() {
      this.tunes.forEach(tune => {
        this.wrapper.classList.toggle(this.CSS.shortCodeHolder + '--' + tune.name.replace(/([A-Z])/g, (g) => `-${g[0].toLowerCase()}`), !!this.data[tune.name]);
  
        if (tune.name === 'stretched') {
            this.block.stretched = !!this.data.stretched;
        }
      });
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('shortcode-tool');
        const title = document.createElement('p');
        title.textContent = "ショートコード:"+this.config.description;
        this.wrapper.appendChild(title);

        //const shortcodeBase = document.createElement('span');
        //shortcodeBase.textContent = `${this.config.name} `;
        //this.wrapper.appendChild(shortcodeBase);

        if (this.config.attributes && this.config.attributes.length > 0) {
            this.config.attributes.forEach(attr => {
                const flexContent = document.createElement('div');
                flexContent.style = "gap: 2em;display: flex;align-items: center;";

                const attrLabel = document.createElement('span');
                attrLabel.textContent = `${attr}`;

                const attributeInput = document.createElement('input');
                attributeInput.type = 'text';
                attributeInput.placeholder = `Value`;

                attributeInput.value = this.data.attributes && this.data.attributes[attr] ? this.data.attributes[attr] : '';
                attributeInput.classList.add("cdx-input");
                attributeInput.addEventListener('input', (event) => {
                    if (!this.data.attributes) {
                        this.data.attributes = {};
                    }
                    this.data.attributes[attr] = event.target.value;
                });

                flexContent.appendChild(attrLabel);
                flexContent.appendChild(attributeInput);
                this.wrapper.appendChild(flexContent);
            });
        }

        return this.wrapper;
    }

    save(blockContent) {
        const attributes = {};

        this.config.attributes.forEach(attr => {
            const input = blockContent.querySelector(`input[placeholder="Value"]`);
            if (input) {
                attributes[attr] = input.value;
            }
        });

        return {
            name: this.config.name,
            attributes: attributes
        };
    }
}