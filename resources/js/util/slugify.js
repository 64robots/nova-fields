import lowerCase from 'lodash/lowerCase'
import slug from 'slugify'

const slugify = (value, separator = '-') => {
    return slug(lowerCase(value), separator)
}

export default slugify
