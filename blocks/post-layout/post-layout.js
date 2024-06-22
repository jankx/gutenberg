import * as React from 'react';
import { registerBlockType } from '@wordpress/blocks';

registerBlockType(
    'jankx/post-layout',
    {
        title: 'Post Layout',
        icon: 'shield',
        category: 'cjankxommon',
        attributes: {
            name: {
                type: 'string',
            },
        },
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
        save: ( { attributes } ) => {
            const { name } = attributes;
            return <p>Hello {name}</p>;
        },
    }
);