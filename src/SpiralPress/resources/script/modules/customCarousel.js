import Carousel from '@vtchinh/gallery-editorjs';
import { IconStretch } from '@codexteam/icons';

export default class CustomCarousel extends Carousel {
  constructor({ data, config, api, readOnly , block  }) {
    super({data, config, api, readOnly});
    this.block = block;
    this._data = {
      ...this._data,
      stretched: data.stretched ?? false,
    }
    this.toggleStretched()
  }
  renderSettings() {
    let otherWrapper = this.tunes.render(this._data);
    let wrapper = document.createElement('div');
    let el = document.createElement('div');
    el.classList.add('ce-popover-item');
    el.classList.add('ce-popover__items');
    el.onclick = () => {
      this.data['stretched'] = !this.data['stretched'];
      el.classList.toggle('ce-popover-item--active',!!this.data.stretched)
      this.toggleStretched()
    }
    el.classList.toggle('ce-popover-item--active',!!this.data.stretched)

    let iconwrapper = document.createElement('div');
    iconwrapper.className = 'ce-popover-item__icon'
    iconwrapper.innerHTML = IconStretch;
    
    let titlewrapper = document.createElement('div');
    titlewrapper.className = "ce-popover-item__title";
    titlewrapper.innerText = "Stretch"
    el.appendChild(iconwrapper)
    el.appendChild(titlewrapper)
    wrapper.appendChild(el);

    let parentWrapper = document.createElement('div');
    parentWrapper.appendChild(otherWrapper)
    parentWrapper.appendChild(wrapper)
    return parentWrapper
  }
  toggleStretched() {
    Promise.resolve().then(() => {    
      this.block.stretched = !!this.data.stretched;
    })
    .catch(err => {
    });
  }
}