"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3680],{3680:(e,n,t)=>{t.r(n),t.d(n,{default:()=>a});const a=[Object.freeze(JSON.parse('{"displayName":"Terraform","fileTypes":["tf","tfvars"],"name":"terraform","patterns":[{"include":"#comments"},{"include":"#attribute_definition"},{"include":"#block"},{"include":"#expressions"}],"repository":{"attribute_access":{"begin":"\\\\.(?!\\\\*)","beginCaptures":{"0":{"name":"keyword.operator.accessor.hcl"}},"comment":"Matches traversal attribute access such as .attr","end":"[A-Za-z][\\\\w-]*|\\\\d*","endCaptures":{"0":{"patterns":[{"comment":"Attribute name","match":"(?!null|false|true)[A-Za-z][\\\\w-]*","name":"variable.other.member.hcl"},{"comment":"Optional attribute index","match":"\\\\d+","name":"constant.numeric.integer.hcl"}]}}},"attribute_definition":{"captures":{"1":{"name":"punctuation.section.parens.begin.hcl"},"2":{"name":"variable.other.readwrite.hcl"},"3":{"name":"punctuation.section.parens.end.hcl"},"4":{"name":"keyword.operator.assignment.hcl"}},"comment":"Identifier \\"=\\" with optional parens","match":"(\\\\()?(\\\\b(?!null\\\\b|false\\\\b|true\\\\b)[A-Za-z][0-9A-Za-z_-]*)(\\\\))?\\\\s*(=(?!=|>))\\\\s*","name":"variable.declaration.hcl"},"attribute_splat":{"begin":"\\\\.","beginCaptures":{"0":{"name":"keyword.operator.accessor.hcl"}},"comment":"Legacy attribute-only splat","end":"\\\\*","endCaptures":{"0":{"name":"keyword.operator.splat.hcl"}}},"block":{"begin":"([\\\\w][\\\\-\\\\w]*)([\\\\s\\\\\\"\\\\-\\\\w]*)(\\\\{)","beginCaptures":{"1":{"patterns":[{"comment":"Known block type","match":"\\\\bdata|check|import|locals|module|output|provider|resource|terraform|variable\\\\b","name":"entity.name.type.terraform"},{"comment":"Unknown block type","match":"\\\\b(?!null|false|true)[A-Za-z][0-9A-Za-z_-]*\\\\b","name":"entity.name.type.hcl"}]},"2":{"patterns":[{"comment":"Block label","match":"[\\\\\\"\\\\-\\\\w]+","name":"variable.other.enummember.hcl"}]},"3":{"name":"punctuation.section.block.begin.hcl"},"5":{"name":"punctuation.section.block.begin.hcl"}},"comment":"This will match Terraform blocks like `resource \\"aws_instance\\" \\"web\\" {` or `module {`","end":"\\\\}","endCaptures":{"0":{"name":"punctuation.section.block.end.hcl"}},"name":"meta.block.hcl","patterns":[{"include":"#comments"},{"include":"#attribute_definition"},{"include":"#block"},{"include":"#expressions"}]},"block_inline_comments":{"begin":"/\\\\*","captures":{"0":{"name":"punctuation.definition.comment.hcl"}},"comment":"Inline comments start with the /* sequence and end with the */ sequence, and may have any characters within except the ending sequence. An inline comment is considered equivalent to a whitespace sequence","end":"\\\\*/","name":"comment.block.hcl"},"brackets":{"begin":"\\\\[","beginCaptures":{"0":{"name":"punctuation.section.brackets.begin.hcl"}},"end":"\\\\]","endCaptures":{"0":{"name":"punctuation.section.brackets.end.hcl"}},"patterns":[{"comment":"Splat operator","match":"\\\\*","name":"keyword.operator.splat.hcl"},{"include":"#comma"},{"include":"#comments"},{"include":"#inline_for_expression"},{"include":"#inline_if_expression"},{"include":"#expressions"},{"include":"#local_identifiers"}]},"char_escapes":{"comment":"Character Escapes","match":"\\\\\\\\[nrt\\"\\\\\\\\]|\\\\\\\\u(\\\\h{8}|\\\\h{4})","name":"constant.character.escape.hcl"},"comma":{"comment":"Commas - used in certain expressions","match":"\\\\,","name":"punctuation.separator.hcl"},"comments":{"patterns":[{"include":"#hash_line_comments"},{"include":"#double_slash_line_comments"},{"include":"#block_inline_comments"}]},"double_slash_line_comments":{"begin":"//","captures":{"0":{"name":"punctuation.definition.comment.hcl"}},"comment":"Line comments start with // sequence and end with the next newline sequence. A line comment is considered equivalent to a newline sequence","end":"$\\\\n?","name":"comment.line.double-slash.hcl"},"expressions":{"patterns":[{"include":"#literal_values"},{"include":"#operators"},{"include":"#tuple_for_expression"},{"include":"#object_for_expression"},{"include":"#brackets"},{"include":"#objects"},{"include":"#attribute_access"},{"include":"#attribute_splat"},{"include":"#functions"},{"include":"#parens"}]},"for_expression_body":{"patterns":[{"comment":"in keyword","match":"\\\\bin\\\\b","name":"keyword.operator.word.hcl"},{"comment":"if keyword","match":"\\\\bif\\\\b","name":"keyword.control.conditional.hcl"},{"match":":","name":"keyword.operator.hcl"},{"include":"#expressions"},{"include":"#comments"},{"include":"#comma"},{"include":"#local_identifiers"}]},"functions":{"begin":"([:\\\\-\\\\w]+)(\\\\()","beginCaptures":{"1":{"patterns":[{"match":"\\\\b(core::)?(abs|abspath|alltrue|anytrue|base64decode|base64encode|base64gzip|base64sha256|base64sha512|basename|bcrypt|can|ceil|chomp|chunklist|cidrhost|cidrnetmask|cidrsubnet|cidrsubnets|coalesce|coalescelist|compact|concat|contains|csvdecode|dirname|distinct|element|endswith|file|filebase64|filebase64sha256|filebase64sha512|fileexists|filemd5|fileset|filesha1|filesha256|filesha512|flatten|floor|format|formatdate|formatlist|indent|index|join|jsondecode|jsonencode|keys|length|log|lookup|lower|matchkeys|max|md5|merge|min|nonsensitive|one|parseint|pathexpand|plantimestamp|pow|range|regex|regexall|replace|reverse|rsadecrypt|sensitive|setintersection|setproduct|setsubtract|setunion|sha1|sha256|sha512|signum|slice|sort|split|startswith|strcontains|strrev|substr|sum|templatefile|textdecodebase64|textencodebase64|timeadd|timecmp|timestamp|title|tobool|tolist|tomap|tonumber|toset|tostring|transpose|trim|trimprefix|trimspace|trimsuffix|try|upper|urlencode|uuid|uuidv5|values|yamldecode|yamlencode|zipmap)\\\\b","name":"support.function.builtin.terraform"},{"match":"\\\\bprovider::[A-Za-z][\\\\w_-]*::[A-Za-z][\\\\w_-]*\\\\b","name":"support.function.provider.terraform"}]},"2":{"name":"punctuation.section.parens.begin.hcl"}},"comment":"Built-in function calls","end":"\\\\)","endCaptures":{"0":{"name":"punctuation.section.parens.end.hcl"}},"name":"meta.function-call.hcl","patterns":[{"include":"#comments"},{"include":"#expressions"},{"include":"#comma"}]},"hash_line_comments":{"begin":"#","captures":{"0":{"name":"punctuation.definition.comment.hcl"}},"comment":"Line comments start with # sequence and end with the next newline sequence. A line comment is considered equivalent to a newline sequence","end":"$\\\\n?","name":"comment.line.number-sign.hcl"},"hcl_type_keywords":{"comment":"Type keywords known to HCL.","match":"\\\\b(any|string|number|bool|list|set|map|tuple|object)\\\\b","name":"storage.type.hcl"},"heredoc":{"begin":"(<<-?)\\\\s*(\\\\w+)\\\\s*$","beginCaptures":{"1":{"name":"keyword.operator.heredoc.hcl"},"2":{"name":"keyword.control.heredoc.hcl"}},"comment":"String Heredoc","end":"^\\\\s*\\\\2\\\\s*$","endCaptures":{"0":{"name":"keyword.control.heredoc.hcl"}},"name":"string.unquoted.heredoc.hcl","patterns":[{"include":"#string_interpolation"}]},"inline_for_expression":{"captures":{"1":{"name":"keyword.control.hcl"},"2":{"patterns":[{"match":"=>","name":"storage.type.function.hcl"},{"include":"#for_expression_body"}]}},"match":"(for)\\\\b(.*)\\\\n"},"inline_if_expression":{"begin":"(if)\\\\b","beginCaptures":{"1":{"name":"keyword.control.conditional.hcl"}},"end":"\\\\n","patterns":[{"include":"#expressions"},{"include":"#comments"},{"include":"#comma"},{"include":"#local_identifiers"}]},"language_constants":{"comment":"Language Constants","match":"\\\\b(true|false|null)\\\\b","name":"constant.language.hcl"},"literal_values":{"patterns":[{"include":"#numeric_literals"},{"include":"#language_constants"},{"include":"#string_literals"},{"include":"#heredoc"},{"include":"#hcl_type_keywords"},{"include":"#named_value_references"}]},"local_identifiers":{"comment":"Local Identifiers","match":"\\\\b(?!null|false|true)[A-Za-z][0-9A-Za-z_-]*\\\\b","name":"variable.other.readwrite.hcl"},"named_value_references":{"comment":"Constant values available only to Terraform.","match":"\\\\b(var|local|module|data|path|terraform)\\\\b","name":"variable.other.readwrite.terraform"},"numeric_literals":{"patterns":[{"captures":{"1":{"name":"punctuation.separator.exponent.hcl"}},"comment":"Integer, no fraction, optional exponent","match":"\\\\b\\\\d+([Ee][+-]?)\\\\d+\\\\b","name":"constant.numeric.float.hcl"},{"captures":{"1":{"name":"punctuation.separator.decimal.hcl"},"2":{"name":"punctuation.separator.exponent.hcl"}},"comment":"Integer, fraction, optional exponent","match":"\\\\b\\\\d+(\\\\.)\\\\d+(?:([Ee][+-]?)\\\\d+)?\\\\b","name":"constant.numeric.float.hcl"},{"comment":"Integers","match":"\\\\b\\\\d+\\\\b","name":"constant.numeric.integer.hcl"}]},"object_for_expression":{"begin":"(\\\\{)\\\\s?(for)\\\\b","beginCaptures":{"1":{"name":"punctuation.section.braces.begin.hcl"},"2":{"name":"keyword.control.hcl"}},"end":"\\\\}","endCaptures":{"0":{"name":"punctuation.section.braces.end.hcl"}},"patterns":[{"match":"=>","name":"storage.type.function.hcl"},{"include":"#for_expression_body"}]},"object_key_values":{"patterns":[{"include":"#comments"},{"include":"#literal_values"},{"include":"#operators"},{"include":"#tuple_for_expression"},{"include":"#object_for_expression"},{"include":"#heredoc"},{"include":"#functions"}]},"objects":{"begin":"\\\\{","beginCaptures":{"0":{"name":"punctuation.section.braces.begin.hcl"}},"end":"\\\\}","endCaptures":{"0":{"name":"punctuation.section.braces.end.hcl"}},"name":"meta.braces.hcl","patterns":[{"include":"#comments"},{"include":"#objects"},{"include":"#inline_for_expression"},{"include":"#inline_if_expression"},{"captures":{"1":{"name":"meta.mapping.key.hcl variable.other.readwrite.hcl"},"2":{"name":"keyword.operator.assignment.hcl","patterns":[{"match":"=>","name":"storage.type.function.hcl"}]}},"comment":"Literal, named object key","match":"\\\\b((?!null|false|true)[A-Za-z][0-9A-Za-z_-]*)\\\\s*(=>?)\\\\s*"},{"captures":{"0":{"patterns":[{"include":"#named_value_references"}]},"1":{"name":"meta.mapping.key.hcl string.quoted.double.hcl"},"2":{"name":"punctuation.definition.string.begin.hcl"},"3":{"name":"punctuation.definition.string.end.hcl"},"4":{"name":"keyword.operator.hcl"}},"comment":"String object key","match":"\\\\b((\\").*(\\"))\\\\s*(=)\\\\s*"},{"begin":"^\\\\s*\\\\(","beginCaptures":{"0":{"name":"punctuation.section.parens.begin.hcl"}},"comment":"Computed object key (any expression between parens)","end":"(\\\\))\\\\s*(=|:)\\\\s*","endCaptures":{"1":{"name":"punctuation.section.parens.end.hcl"},"2":{"name":"keyword.operator.hcl"}},"name":"meta.mapping.key.hcl","patterns":[{"include":"#named_value_references"},{"include":"#attribute_access"}]},{"include":"#object_key_values"}]},"operators":{"patterns":[{"match":">=","name":"keyword.operator.hcl"},{"match":"<=","name":"keyword.operator.hcl"},{"match":"==","name":"keyword.operator.hcl"},{"match":"!=","name":"keyword.operator.hcl"},{"match":"\\\\+","name":"keyword.operator.arithmetic.hcl"},{"match":"-","name":"keyword.operator.arithmetic.hcl"},{"match":"\\\\*","name":"keyword.operator.arithmetic.hcl"},{"match":"\\\\/","name":"keyword.operator.arithmetic.hcl"},{"match":"\\\\%","name":"keyword.operator.arithmetic.hcl"},{"match":"\\\\&\\\\&","name":"keyword.operator.logical.hcl"},{"match":"\\\\|\\\\|","name":"keyword.operator.logical.hcl"},{"match":"!","name":"keyword.operator.logical.hcl"},{"match":">","name":"keyword.operator.hcl"},{"match":"<","name":"keyword.operator.hcl"},{"match":"\\\\?","name":"keyword.operator.hcl"},{"match":"\\\\.\\\\.\\\\.","name":"keyword.operator.hcl"},{"match":":","name":"keyword.operator.hcl"},{"match":"=>","name":"keyword.operator.hcl"}]},"parens":{"begin":"\\\\(","beginCaptures":{"0":{"name":"punctuation.section.parens.begin.hcl"}},"comment":"Parens - matched *after* function syntax","end":"\\\\)","endCaptures":{"0":{"name":"punctuation.section.parens.end.hcl"}},"patterns":[{"include":"#comments"},{"include":"#expressions"}]},"string_interpolation":{"begin":"(?<![%$])([%$]{)","beginCaptures":{"1":{"name":"keyword.other.interpolation.begin.hcl"}},"comment":"String interpolation","end":"\\\\}","endCaptures":{"0":{"name":"keyword.other.interpolation.end.hcl"}},"name":"meta.interpolation.hcl","patterns":[{"comment":"Trim left whitespace","match":"\\\\~\\\\s","name":"keyword.operator.template.left.trim.hcl"},{"comment":"Trim right whitespace","match":"\\\\s\\\\~","name":"keyword.operator.template.right.trim.hcl"},{"comment":"if/else/endif and for/in/endfor directives","match":"\\\\b(if|else|endif|for|in|endfor)\\\\b","name":"keyword.control.hcl"},{"include":"#expressions"},{"include":"#local_identifiers"}]},"string_literals":{"begin":"\\"","beginCaptures":{"0":{"name":"punctuation.definition.string.begin.hcl"}},"comment":"Strings","end":"\\"","endCaptures":{"0":{"name":"punctuation.definition.string.end.hcl"}},"name":"string.quoted.double.hcl","patterns":[{"include":"#string_interpolation"},{"include":"#char_escapes"}]},"tuple_for_expression":{"begin":"(\\\\[)\\\\s?(for)\\\\b","beginCaptures":{"1":{"name":"punctuation.section.brackets.begin.hcl"},"2":{"name":"keyword.control.hcl"}},"end":"\\\\]","endCaptures":{"0":{"name":"punctuation.section.brackets.end.hcl"}},"patterns":[{"include":"#for_expression_body"}]}},"scopeName":"source.hcl.terraform","aliases":["tf","tfvars"]}'))]}}]);