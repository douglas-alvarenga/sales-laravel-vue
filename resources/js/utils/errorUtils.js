export function mapApiErrors(apiErrors) {
    const objErrors = {};
    for (const field in apiErrors) {
        if (
            apiErrors.hasOwnProperty(field) &&
            Array.isArray(apiErrors[field]) &&
            apiErrors[field].length
        ) {
            objErrors[field] = apiErrors[field][0];
        }
    }
    return objErrors;
}
