import * as _ from 'lodash'

export function difference (object, base) {
  function changes (object, base) {
    let arrayIndexCounter = 0
    return _.transform(object, function (result, value, key) {
      if (!value) value = null
      if (!base[key]) base[key] = null

      if (!_.isEqual(value, base[key])) {
        let resultKey = _.isArray(base) ? arrayIndexCounter++ : key
        result[resultKey] = (_.isObject(value) && _.isObject(base[key])) ? changes(value, base[key]) : value
      }
    })
  }
  return changes(object, base)
}
