"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4858,9376],{9376:(e,t,n)=>{n.r(t),n.d(t,{default:()=>m});var a=n(3235),s=n(4858),o=n(9123),r=n(4059);const i=Object.freeze(JSON.parse('{"displayName":"HTTP","fileTypes":["http","rest"],"name":"http","patterns":[{"begin":"^\\\\s*(?=curl)","end":"^\\\\s*(\\\\#{3,}.*?)?\\\\s*$","endCaptures":{"0":{"name":"comment.line.sharp.http"}},"name":"http.request.curl","patterns":[{"include":"source.shell"}]},{"begin":"\\\\s*(?=(\\\\[|{[^{]))","end":"^\\\\s*(\\\\#{3,}.*?)?\\\\s*$","endCaptures":{"0":{"name":"comment.line.sharp.http"}},"name":"http.request.body.json","patterns":[{"include":"source.json"}]},{"begin":"^\\\\s*(?=<\\\\S)","end":"^\\\\s*(\\\\#{3,}.*?)?\\\\s*$","endCaptures":{"0":{"name":"comment.line.sharp.http"}},"name":"http.request.body.xml","patterns":[{"include":"text.xml"}]},{"begin":"\\\\s*(?=(query|mutation))","end":"^\\\\s*(\\\\#{3,}.*?)?\\\\s*$","endCaptures":{"0":{"name":"comment.line.sharp.http"}},"name":"http.request.body.graphql","patterns":[{"include":"source.graphql"}]},{"begin":"\\\\s*(?=(query|mutation))","end":"^\\\\{\\\\s*$","name":"http.request.body.graphql","patterns":[{"include":"source.graphql"}]},{"include":"#metadata"},{"include":"#comments"},{"captures":{"1":{"name":"keyword.other.http"},"2":{"name":"variable.other.http"},"3":{"name":"string.other.http"}},"match":"^\\\\s*(@)([^\\\\s=]+)\\\\s*=\\\\s*(.*?)\\\\s*$","name":"http.filevariable"},{"captures":{"1":{"name":"keyword.operator.http"},"2":{"name":"variable.other.http"},"3":{"name":"string.other.http"}},"match":"^\\\\s*(\\\\?|&)([^=\\\\s]+)=(.*)$","name":"http.query"},{"captures":{"1":{"name":"entity.name.tag.http"},"2":{"name":"keyword.other.http"},"3":{"name":"string.other.http"}},"match":"^([\\\\w\\\\-]+)\\\\s*(:)\\\\s*([^/].*?)\\\\s*$","name":"http.headers"},{"include":"#request-line"},{"include":"#response-line"}],"repository":{"comments":{"patterns":[{"match":"^\\\\s*\\\\#{1,}.*$","name":"comment.line.sharp.http"},{"match":"^\\\\s*\\\\/{2,}.*$","name":"comment.line.double-slash.http"}]},"metadata":{"patterns":[{"captures":{"1":{"name":"entity.other.attribute-name"},"2":{"name":"punctuation.definition.block.tag.metadata"},"3":{"name":"entity.name.type.http"}},"match":"^\\\\s*\\\\#{1,}\\\\s+(?:((@)name)\\\\s+([^\\\\s\\\\.]+))$","name":"comment.line.sharp.http"},{"captures":{"1":{"name":"entity.other.attribute-name"},"2":{"name":"punctuation.definition.block.tag.metadata"},"3":{"name":"entity.name.type.http"}},"match":"^\\\\s*\\\\/{2,}\\\\s+(?:((@)name)\\\\s+([^\\\\s\\\\.]+))$","name":"comment.line.double-slash.http"},{"captures":{"1":{"name":"entity.other.attribute-name"},"2":{"name":"punctuation.definition.block.tag.metadata"}},"match":"^\\\\s*\\\\#{1,}\\\\s+((@)note)\\\\s*$","name":"comment.line.sharp.http"},{"captures":{"1":{"name":"entity.other.attribute-name"},"2":{"name":"punctuation.definition.block.tag.metadata"}},"match":"^\\\\s*\\\\/{2,}\\\\s+((@)note)\\\\s*$","name":"comment.line.double-slash.http"},{"captures":{"1":{"name":"entity.other.attribute-name"},"2":{"name":"punctuation.definition.block.tag.metadata"},"3":{"name":"variable.other.http"},"4":{"name":"string.other.http"}},"match":"^\\\\s*\\\\#{1,}\\\\s+(?:((@)prompt)\\\\s+([^\\\\s]+)(?:\\\\s+(.*))?\\\\s*)$","name":"comment.line.sharp.http"},{"captures":{"1":{"name":"entity.other.attribute-name"},"2":{"name":"punctuation.definition.block.tag.metadata"},"3":{"name":"variable.other.http"},"4":{"name":"string.other.http"}},"match":"^\\\\s*\\\\/{2,}\\\\s+(?:((@)prompt)\\\\s+([^\\\\s]+)(?:\\\\s+(.*))?\\\\s*)$","name":"comment.line.double-slash.http"}]},"protocol":{"patterns":[{"captures":{"1":{"name":"keyword.other.http"},"2":{"name":"constant.numeric.http"}},"match":"(HTTP)/(\\\\d+.\\\\d+)","name":"http.version"}]},"request-line":{"captures":{"1":{"name":"keyword.control.http"},"2":{"name":"const.language.http"},"3":{"patterns":[{"include":"#protocol"}]}},"match":"(?i)^(?:(get|post|put|delete|patch|head|options|connect|trace|lock|unlock|propfind|proppatch|copy|move|mkcol|mkcalendar|acl|search)\\\\s+)\\\\s*(.+?)(?:\\\\s+(HTTP\\\\/\\\\S+))?$","name":"http.requestline"},"response-line":{"captures":{"1":{"patterns":[{"include":"#protocol"}]},"2":{"name":"constant.numeric.http"},"3":{"name":"string.other.http"}},"match":"(?i)^\\\\s*(HTTP\\\\/\\\\S+)\\\\s([1-5]\\\\d\\\\d)\\\\s(.*)$","name":"http.responseLine"}},"scopeName":"source.http","embeddedLangs":["shellscript","json","xml","graphql"]}')),m=[...a.default,...s.default,...o.default,...r.default,i]},4858:(e,t,n)=>{n.r(t),n.d(t,{default:()=>a});const a=[Object.freeze(JSON.parse('{"displayName":"JSON","name":"json","patterns":[{"include":"#value"}],"repository":{"array":{"begin":"\\\\[","beginCaptures":{"0":{"name":"punctuation.definition.array.begin.json"}},"end":"\\\\]","endCaptures":{"0":{"name":"punctuation.definition.array.end.json"}},"name":"meta.structure.array.json","patterns":[{"include":"#value"},{"match":",","name":"punctuation.separator.array.json"},{"match":"[^\\\\s\\\\]]","name":"invalid.illegal.expected-array-separator.json"}]},"comments":{"patterns":[{"begin":"/\\\\*\\\\*(?!/)","captures":{"0":{"name":"punctuation.definition.comment.json"}},"end":"\\\\*/","name":"comment.block.documentation.json"},{"begin":"/\\\\*","captures":{"0":{"name":"punctuation.definition.comment.json"}},"end":"\\\\*/","name":"comment.block.json"},{"captures":{"1":{"name":"punctuation.definition.comment.json"}},"match":"(//).*$\\\\n?","name":"comment.line.double-slash.js"}]},"constant":{"match":"\\\\b(?:true|false|null)\\\\b","name":"constant.language.json"},"number":{"match":"-?(?:0|[1-9]\\\\d*)(?:(?:\\\\.\\\\d+)?(?:[eE][+-]?\\\\d+)?)?","name":"constant.numeric.json"},"object":{"begin":"\\\\{","beginCaptures":{"0":{"name":"punctuation.definition.dictionary.begin.json"}},"end":"\\\\}","endCaptures":{"0":{"name":"punctuation.definition.dictionary.end.json"}},"name":"meta.structure.dictionary.json","patterns":[{"comment":"the JSON object key","include":"#objectkey"},{"include":"#comments"},{"begin":":","beginCaptures":{"0":{"name":"punctuation.separator.dictionary.key-value.json"}},"end":"(,)|(?=\\\\})","endCaptures":{"1":{"name":"punctuation.separator.dictionary.pair.json"}},"name":"meta.structure.dictionary.value.json","patterns":[{"comment":"the JSON object value","include":"#value"},{"match":"[^\\\\s,]","name":"invalid.illegal.expected-dictionary-separator.json"}]},{"match":"[^\\\\s}]","name":"invalid.illegal.expected-dictionary-separator.json"}]},"objectkey":{"begin":"\\"","beginCaptures":{"0":{"name":"punctuation.support.type.property-name.begin.json"}},"end":"\\"","endCaptures":{"0":{"name":"punctuation.support.type.property-name.end.json"}},"name":"string.json support.type.property-name.json","patterns":[{"include":"#stringcontent"}]},"string":{"begin":"\\"","beginCaptures":{"0":{"name":"punctuation.definition.string.begin.json"}},"end":"\\"","endCaptures":{"0":{"name":"punctuation.definition.string.end.json"}},"name":"string.quoted.double.json","patterns":[{"include":"#stringcontent"}]},"stringcontent":{"patterns":[{"match":"\\\\\\\\(?:[\\"\\\\\\\\/bfnrt]|u[0-9a-fA-F]{4})","name":"constant.character.escape.json"},{"match":"\\\\\\\\.","name":"invalid.illegal.unrecognized-string-escape.json"}]},"value":{"patterns":[{"include":"#constant"},{"include":"#number"},{"include":"#string"},{"include":"#array"},{"include":"#object"},{"include":"#comments"}]}},"scopeName":"source.json"}'))]}}]);