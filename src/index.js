const { registerBlockType } = wp.blocks;
const { RichText } = wp.editor;

registerBlockType('my-plugin/my-custom-block', {
    title: 'My Custom Block',
    icon: 'smiley',
    category: 'common',
    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: 'p',
        },
    },
    edit: ( props ) => {
        const { attributes: { content }, setAttributes, className } = props;
        const onChangeContent = ( newContent ) => {
            setAttributes({ content: newContent });
        };

        return (
            < RichText
                tagName = "p"
                className = { className }
                onChange = { onChangeContent }
                value = { content }
                placeholder = { __('Write your custom message', 'text-domain') }
            /  >
        );
    },
    save: ( props ) => {
        return < RichText.Content tagName = "p" value = { props.attributes.content } /  > ;
    },
});