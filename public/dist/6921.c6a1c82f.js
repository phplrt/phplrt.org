"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6921],{6921:(e,t,a)=>{a.r(t),a.d(t,{default:()=>n});const n=[Object.freeze(JSON.parse('{"displayName":"Hy","name":"hy","patterns":[{"include":"#all"}],"repository":{"all":{"patterns":[{"include":"#comment"},{"include":"#constants"},{"include":"#keywords"},{"include":"#strings"},{"include":"#operators"},{"include":"#keysym"},{"include":"#builtin"},{"include":"#symbol"}]},"builtin":{"patterns":[{"match":"\\\\b(abs|all|any|ascii|bin|breakpoint|callable|chr|compile|delattr|dir|divmod|eval|exec|format|getattr|globals|hasattr|hash|hex|id|input|isinstance|issubclass|iter|aiter|len|locals|max|min|next|anext|oct|ord|pow|print|repr|round|setattr|sorted|sum|vars|False|None|True|NotImplemented|bool|memoryview|bytearray|bytes|classmethod|complex|dict|enumerate|filter|float|frozenset|property|int|list|map|object|range|reversed|set|slice|staticmethod|str|super|tuple|type|zip|open|quit|exit|copyright|credits|help)[\\\\s)]","name":"storage.builtin.hy"},{"match":"(?<=\\\\(\\\\s*)\\\\.\\\\.\\\\.[\\\\s)]","name":"storage.builtin.dots.hy"}]},"comment":{"patterns":[{"match":"(;).*$","name":"comment.line.hy"}]},"constants":{"patterns":[{"match":"(?<=[{\\\\[(\\\\s])(\\\\d+(\\\\.\\\\d+)?|(#x)[0-9a-fA-F]+|(#o)[0-7]+|(#b)[01]+)(?=[\\\\s;()\'\\",\\\\[\\\\]{}])","name":"constant.numeric.hy"}]},"keysym":{"match":"(?<![\\\\.:\\\\w_\\\\-=!@$%^&?\\\\/<>*]):[\\\\.:\\\\w_\\\\-=!@$%^&?\\\\/<>*]*","name":"variable.other.constant"},"keywords":{"patterns":[{"match":"\\\\b(and|await|match|let|annotate|assert|break|chainc|cond|continue|deftype|do|except\\\\*?|finally|else|defreader|([dgls])?for|set[vx]|defclass|defmacro|del|export|eval-and-compile|eval-when-compile|get|global|if|import|(de)?fn|nonlocal|not-in|or|(quasi)?quote|require|return|cut|raise|try|unpack-iterable|unpack-mapping|unquote|unquote-splice|when|while|with|yield|local-macros|in|is|py(s)?|pragma|nonlocal|(is-)?not)[\\\\s)]","name":"keyword.control.hy"},{"match":"(?<=\\\\(\\\\s*)\\\\.[\\\\s)]","name":"keyword.control.dot.hy"}]},"operators":{"patterns":[{"match":"(?<=\\\\()\\\\s*(\\\\+=?|\\\\/\\\\/?=?|\\\\*\\\\*?=?|--?=?|[!<>]?=|@=?|%=?|<<?=?|>>?=?|&=?|\\\\|=?|\\\\^|~@|~=?|#\\\\*\\\\*?)","name":"keyword.control.hy"}]},"strings":{"begin":"(f?\\"|}(?=[^\\n]*?[{\\"]))","end":"(\\"|(?<=[\\"}][^\\n]*?){)","name":"string.quoted.double.hy","patterns":[{"match":"\\\\\\\\.","name":"constant.character.escape.hy"}]},"symbol":{"match":"(?<![\\\\.:\\\\w_\\\\-=!@$%^&?/<>*#])[\\\\.a-zA-ZΑ-Ωα-ω_\\\\-=!@$%^<?/<>*#][\\\\.:\\\\w_\\\\-=!@$%^&?/<>*#]*","name":"variable.other.hy"}},"scopeName":"source.hy"}'))]}}]);