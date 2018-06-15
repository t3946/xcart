import {breakpoints} from "../settings/global";

export default function getMediaJs(width)
{
    width = width || 0;
    let name = 'small';
    for (let pointName in breakpoints) {

        if(width < breakpoints[pointName]){
            return name;
        }
        name = pointName;
    }

    return 'large';
}