/*! For license information please see jankx-gutenberg.js.LICENSE.txt */
(()=>{"use strict";var t={933:(t,e,o)=>{var r=o(594),n=Symbol.for("react.element"),s=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),a=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,i={key:!0,ref:!0,__self:!0,__source:!0};function u(t,e,o){var r,u={},c=null,f=null;for(r in void 0!==o&&(c=""+o),void 0!==e.key&&(c=""+e.key),void 0!==e.ref&&(f=e.ref),e)s.call(e,r)&&!i.hasOwnProperty(r)&&(u[r]=e[r]);if(t&&t.defaultProps)for(r in e=t.defaultProps)void 0===u[r]&&(u[r]=e[r]);return{$$typeof:n,type:t,key:c,ref:f,props:u,_owner:a.current}}e.jsx=u,e.jsxs=u},671:(t,e,o)=>{t.exports=o(933)},594:t=>{t.exports=React}},e={};function o(r){var n=e[r];if(void 0!==n)return n.exports;var s=e[r]={exports:{}};return t[r](s,s.exports,o),s.exports}const r=wp.blocks;o(594);var n=o(671);function s(t){return s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},s(t)}var a={"jankx/posts":{getType:function(){return"jankx/posts"},edit:function(t){var e=t.className,o=t.setAttributes,r=t.attributes.name;return(0,n.jsxs)("div",{className:e,children:[(0,n.jsxs)("p",{children:["Hello ",r]}),(0,n.jsx)("input",{type:"text",value:r,onChange:function(t){return o({name:t.target.value})}})]})},save:function(){},customizeAttributes:function(t){return t}},"jankx/page-selector":{},"jankx/link-tabs":{},"jankx/posts-tabs":{},"jankx/contact-form-7":{},"jankx/social-sharing":{}},i=window.jankx_blocks||[];Object.keys(i).forEach((function(t){var e=i[t],o=a[t]||null;"object"===s(o)&&(e.save=o.save||function(){},e.edit=o.edit||function(){},"function"==typeof o.customizeAttributes&&(e=o.customizeAttributes(e))),console.log(t,e),(0,r.registerBlockType)(t,e)}))})();