import React from 'preact'
import { Link } from 'preact-router/match';

const NavigateMenuItem = (props) =>{
    return (
        <Link activeClassName="active" href={props.link}>
            <div>
                <div>
                    <image src={props.image}/>
                </div>
                <span>{props.text}</span>
            </div>
        </Link>
    )
}

export default NavigateMenuItem