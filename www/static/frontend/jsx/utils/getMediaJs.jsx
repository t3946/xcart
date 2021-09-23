import {breakpoints} from "../settings/global";

export default function getMediaJs(width) {
    width = width || 0;
    let name = breakpoints[0][0];
    for (let point of breakpoints) {

        let pointName = point[0];
        let pointWidth = point[1];

        if (width < pointWidth) {
            return name;
        }
        name = pointName;
    }

    return breakpoints[breakpoints.length - 1][0];
}