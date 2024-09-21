
export default class BaseRest{
    getFullUrl(suffixUrl){
        console.log("url for prefix ");
        console.log(import.meta.env.VITE_BASE_URL);
        console.log(import.meta.env.MODE);

        return import.meta.env.VITE_BASE_URL+ suffixUrl;
    }
}
