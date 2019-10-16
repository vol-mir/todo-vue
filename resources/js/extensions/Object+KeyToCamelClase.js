import * as _ from 'lodash'

export function keyToCamelClase (object) {
  if (typeof object !== 'object') return object

  for (let oldName in object) {
    // to Camel
    let newName = _.camelCase(oldName)

    // Only process if names are different
    if (newName !== oldName) {
      // Check for the old property name to avoid a ReferenceError in strict mode.
      if (object.hasOwnProperty(oldName)) {
        object[newName] = object[oldName]
        delete object[oldName]
      }
    }

    // Recursion
    if (typeof object[newName] === 'object') {
      object[newName] = keyToCamelClase(object[newName])
    }
  }
  return object
}
