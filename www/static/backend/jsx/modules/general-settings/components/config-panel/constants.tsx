export const convertObjectToArray = (ob: object) => {
    let arNew = [];
    for (const key in ob) {
        arNew[key] = ob[key];
    }
    console.log(typeof arNew);
    return arNew;
}