import { registerBlockType } from "@wordpress/blocks";

import posts from "./posts";
import pageSelector from "./page-selector";
import linkTabs from "./link-tabs";
import postsTabs from "./posts-tabs";
import contactForm7 from "./contact-form-7";
import socialSharing from "./social-sharing";

const blockMethods = {
  'jankx/posts': posts,
  'jankx/page-selector': pageSelector,
  'jankx/link-tabs': linkTabs,
  'jankx/posts-tabs': postsTabs,
  'jankx/contact-form-7': contactForm7,
  'jankx/social-sharing': socialSharing,
};

const blocks = window["jankx_blocks"] || [];

Object.keys(blocks).forEach((blockType) => {
  let blockAttributes = blocks[blockType];

  const blockMethod = blockMethods[blockType] || null;
  if (typeof blockMethod === 'object') {
    blockAttributes.save = blockMethod.save || function(){};
    blockAttributes.edit = blockMethod.edit || function(){};

    if (typeof blockMethod.customizeAttributes === 'function') {
      blockAttributes = blockMethod.customizeAttributes(blockAttributes);
    }
  }
  registerBlockType(blockType, blockAttributes);
});
