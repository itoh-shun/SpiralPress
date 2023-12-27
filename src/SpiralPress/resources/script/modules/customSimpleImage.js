import SimpleImage from '@editorjs/simple-image';

export default class CustomSimpleImage extends SimpleImage {
    constructor({ data, config, api, block , readOnly }) {
        super({data, config, api ,readOnly});
        this.block = block;
    }
    _acceptTuneView() {
      this.tunes.forEach(tune => {
        this.nodes.imageHolder.classList.toggle(this.CSS.imageHolder + '--' + tune.name.replace(/([A-Z])/g, (g) => `-${g[0].toLowerCase()}`), !!this.data[tune.name]);
  
        if (tune.name === 'stretched') {
            this.block.stretched = !!this.data.stretched;
        }
      });
    }
}