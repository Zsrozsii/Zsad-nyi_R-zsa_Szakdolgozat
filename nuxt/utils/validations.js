export const required = (value) => !!value || "Field is required"

export const selectObjectRequired = (value) => {
    return !!value?.text || !!value?.value || "Field is required"
}

export const validEmail = (value) => {
    const validEmailRe =
        /^(([^<>()\\[\]\\.,;:\s@"]+(\.[^<>()\\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

    return (
        validEmailRe.test(String(value).toLowerCase()) ||
        "Invalid email address"
    )
}

export const isNumber = (value) => {
    return !isNaN(value) || "Field must be a number"
}

export const gt = (value, min) => {
    return isNaN(value) || value > min || `Value must be more than ${min}`
}

export const gte = (value, min) => {
    return isNaN(value) || value >= min || `Value must be at least ${min}`
}

export const lt = (value, max) => {
    return isNaN(value) || value < max || `Value must be less than ${max}`
}

export const lte = (value, max) => {
    return isNaN(value) || value <= max || `Value must be maximum ${max}`
}

export const minLength = (value, length) =>
    value.length >= length || `Field must contain at least ${length} characters`
