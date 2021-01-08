const jsyaml = require('js-yaml')

let source
let element

function EntityEncoder() {
}

EntityEncoder.prototype.TYPE = 'taskcamp_entity'

EntityEncoder.prototype.supportsDecoding = function(format) {
  return format === this.TYPE
}

EntityEncoder.prototype.supportsEncoding = function(format) {
  return false
}

EntityEncoder.prototype.decode = function(data, format) {
  source = data.trim()

  // Create an object of the first line, which is an XML tag.
  const first_line = source.split('\n')[0]
  let temp = document.createElement('div')
  temp.innerHTML = first_line
  element = temp.childNodes[0]

  let decoded = {}
  decoded.body = getBody()
  decoded.data = getData()
  decoded.properties = getProperties()
  decoded.title = getTitle()
  decoded.type = getType()
  return decoded
}

/**
 * Get the object name.
 *
 * @returns {string}
 */
function getType() {
  return element.tagName ? element.tagName.toLowerCase() : ''
}

function getProperties() {
  if (!element.attributes) {
    return []
  }
  const attributes = element.attributes
  let result = {}
  for (let i = 0; i < attributes.length; i++) {
    result[attributes[i].name] = attributes[i].value
  }
  return result
}

function getData() {
  let data = source
  $match = new RegExp('-{3,}')
  if ($match.test(data)) {
    let split = $match.exec(data)
    data = data.split(split)[0]
  } else {
    $match = new RegExp('#[^#]*')
    let split = $match.exec(data)
    data = data.split(split)[0]
  }
  data = data.split('\n')
  data.shift()
  data = data.join('\n')
  return jsyaml.load(data) || {}
}

function getTitle() {
  $match = new RegExp('#\\s*(.+)', 'g')
  let title = $match.exec(source) || []
  return title.length ? title[1] : ''
}

function getBody() {
  let body = source.split(getTitle())
  return body.length ? body[1].trim() : ''
}

module.exports = EntityEncoder
