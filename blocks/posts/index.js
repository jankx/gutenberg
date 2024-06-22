import * as React from "react";

export default {
    getType: () => 'jankx/posts',
    edit: ( { className, setAttributes, attributes } ) => {
        const { name } = attributes;
        return (
            <div className={ className }>
                <p>Hello {name}</p>
                <input
                    type='text'
                    value={name}
                    onChange={(event) => setAttributes({name: event.target.value})}
                />
            </div>
        );
    },
    save: function(){},
    customizeAttributes: (attributes) => attributes,
}
