import { registerBlockType } from "@wordpress/blocks";

import PostLayout from "./post-layout";

const blockMethods = {
  'jankx/post-layout': PostLayout,
};

const blocks = window["jankx_blocks"] || [];

Object.keys(blocks).forEach((blockType) => {
  let blockAttributes = blocks[blockType];

  const blockMethod = blockMethods[blockType] || null;
  if (typeof blockMethod === 'object') {
    if (typeof blockMethod.customizeAttributes === 'function') {
        blockAttributes = blockMethod.customizeAttributes(blockAttributes);
    }
    blockAttributes.save = blockMethod.save;
    blockAttributes.edit = blockMethod.edit;
  }
  registerBlockType(blockType, blockAttributes);
});
