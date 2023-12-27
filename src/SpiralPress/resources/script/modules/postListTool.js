export default class PostListTool {
    static get toolbox() {
      return {
        title: 'PostList',
        icon: `<span>PostList</span>`,
      }
    }

    constructor({ data, config }) {
      this.data = data
      this.config = config
      this.wrapper = undefined
    }

    render() {
      this.wrapper = document.createElement('div');
      this.wrapper.classList.add('p-4', 'bg-gray-100', 'rounded', 'flex', 'flex-col', 'gap-2');
    
      // Limitのための入力フィールドとラベルを作成
      const limitContainer = this.createLabeledInput('Limit', 'number', 'limit', 'Enter limit', 100);
    
      // カテゴリ選択用のセレクトボックスとラベルを作成
      let options = [];
      this.config.categories.forEach(function (category) {
        options.push([category.categoryShareId , category.categoryName])
      })

      const categoryContainer = this.createLabeledSelect('カテゴリ', 'category', options);
    
      // 表示形式選択用のセレクトボックスとラベルを作成
      const viewTypeContainer = this.createLabeledSelect('表示形式', 'viewType', [['title','タイトルのみ'], ['card','カード表示']]);
    
      // ラッパーに各コンテナを追加
      this.wrapper.appendChild(limitContainer);
      this.wrapper.appendChild(categoryContainer);
      this.wrapper.appendChild(viewTypeContainer);
    
      // Limit入力フィールドの値を設定
      if (this.data.limit) {
        const limitInput = limitContainer.querySelector('input[name="limit"]');
        limitInput.value = this.data.limit;
      }

      // カテゴリ選択用セレクトボックスの選択状態を設定
      const categorySelect = categoryContainer.querySelector('select[name="category"]');
      if (this.data.categoryShareId) {
        categorySelect.value = this.data.categoryShareId;
      }

      // 表示形式選択用セレクトボックスの選択状態を設定
      const viewTypeSelect = viewTypeContainer.querySelector('select[name="viewType"]');
      if (this.data.viewType) {
        viewTypeSelect.value = this.data.viewType;
      }

      return this.wrapper;
    }
    
    // ラベル付きの入力フィールドを作成する関数
    createLabeledInput(labelText, inputType, inputName, placeholder, max = null) {
      const container = document.createElement('div');
      container.classList.add('form-control');
    
      const label = document.createElement('label');
      label.classList.add('label');
      label.textContent = labelText;
    
      const input = document.createElement('input');
      input.type = inputType;
      input.name = inputName;
      input.placeholder = placeholder;
      input.classList.add('input', 'input-bordered', 'w-full');
      if (max) {
        input.max = max;
      }
    
      container.appendChild(label);
      container.appendChild(input);
    
      return container;
    }
    
    // ラベル付きのセレクトボックスを作成する関数
    createLabeledSelect(labelText, selectName, options) {
      const container = document.createElement('div');
      container.classList.add('form-control');
    
      const label = document.createElement('label');
      label.classList.add('label');
      label.textContent = labelText;
    
      const select = document.createElement('select');
      select.name = selectName;
      select.classList.add('select', 'select-bordered', 'w-full');
      options.forEach(o => {
        const option = document.createElement('option');
        option.value = o[0];
        option.textContent = o[1];
        select.appendChild(option);
      });
    
      container.appendChild(label);
      container.appendChild(select);
    
      return container;
    }
    uniqid(prefix = "", random = false) {
      const sec = Date.now() * 1000 + Math.random() * 1000;
      const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
      return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}`:""}`;
    }
    save(blockContent) {
      const categorySelect = blockContent.querySelector('select[name="category"]');
      const viewTypeSelect = blockContent.querySelector('select[name="viewType"]');

      const limitInput = blockContent.querySelector('input[name="limit"]');
      const limit = limitInput.value ? parseInt(limitInput.value, 10) : null;
       
      return {
        id: this.data.id ?? this.uniqid(),
        categoryShareId: categorySelect.value,
        viewType: viewTypeSelect.value,
        limit: limit,
      };
    }
  }
