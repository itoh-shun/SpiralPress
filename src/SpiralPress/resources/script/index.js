import { fetchApi } from './modules/fetchApi';
window.fetchApi = fetchApi;

import Sortable, { MultiDrag, Swap } from 'sortablejs';
window.Sortable = Sortable;

import EditorJS from '@editorjs/editorjs';
window.EditorJS = EditorJS

import Embed from '@editorjs/embed';
window.Embed = Embed

import Header from '@editorjs/header';
window.Header = Header

import ImageTool from '@editorjs/image';
window.ImageTool  = ImageTool 
//import SimpleImage from '@editorjs/simple-image';
//window.SimpleImage  = SimpleImage 

import Paragraph from '@editorjs/paragraph';
window.Paragraph  = Paragraph 

import Delimiter from '@editorjs/delimiter';
window.Delimiter  = Delimiter

import Warning from '@editorjs/warning';
window.Warning  = Warning

import Quote from '@editorjs/quote';
window.Quote  = Quote

import NestedList from '@editorjs/nested-list';
window.NestedList  = NestedList

import Table from '@editorjs/table'
window.Table  = Table

import Underline from '@editorjs/underline';
window.Underline  = Underline

import InlineCode from '@editorjs/inline-code';
window.InlineCode  = InlineCode

import Marker from '@editorjs/marker';
window.Marker  = Marker

import CodeTool from '@editorjs/code';
window.CodeTool  = CodeTool

import RawTool from '@editorjs/raw';
window.RawTool  = RawTool

import Checklist from '@editorjs/checklist'
window.Checklist  = Checklist

import LinkTool from '@editorjs/link';
window.LinkTool  = LinkTool

import TextVariantTune from '@editorjs/text-variant-tune';
window.TextVariantTune  = TextVariantTune

import PostListTool from './modules/postListTool';
window.PostListTool  = PostListTool

import AttachesTool from '@editorjs/attaches';
window.AttachesTool  = AttachesTool

import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()