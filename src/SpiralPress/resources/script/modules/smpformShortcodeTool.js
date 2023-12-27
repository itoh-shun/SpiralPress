import CustomShortcodeTool from './customShortcodeTool';

export default class SmpformShortcodeTool extends CustomShortcodeTool {
    static get toolbox() {
        return {
            title: 'スパイラルフォームのショートコード',
            icon: '<svg>...</svg>' // カスタムアイコンを設定できます
        };
    }
}