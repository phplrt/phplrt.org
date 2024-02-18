"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4531,976],{976:(e,a,t)=>{t.r(a),t.d(a,{default:()=>r});var r=[Object.freeze({displayName:"HLSL",name:"hlsl",patterns:[{begin:"/\\*",end:"\\*/",name:"comment.line.block.hlsl"},{begin:"//",end:"$",name:"comment.line.double-slash.hlsl"},{match:"\\b[0-9]+\\.[0-9]*(F|f)?\\b",name:"constant.numeric.decimal.hlsl"},{match:"(\\.([0-9]+)(F|f)?)\\b",name:"constant.numeric.decimal.hlsl"},{match:"\\b([0-9]+(F|f)?)\\b",name:"constant.numeric.decimal.hlsl"},{match:"\\b(0(x|X)[0-9a-fA-F]+)\\b",name:"constant.numeric.hex.hlsl"},{match:"\\b(false|true)\\b",name:"constant.language.hlsl"},{match:"^\\s*#\\s*(define|elif|else|endif|ifdef|ifndef|if|undef|include|line|error|pragma)",name:"keyword.preprocessor.hlsl"},{match:"\\b(break|case|continue|default|discard|do|else|for|if|return|switch|while)\\b",name:"keyword.control.hlsl"},{match:"\\b(compile)\\b",name:"keyword.control.fx.hlsl"},{match:"\\b(typedef)\\b",name:"keyword.typealias.hlsl"},{match:"\\b(bool([1-4](x[1-4])?)?|double([1-4](x[1-4])?)?|dword|float([1-4](x[1-4])?)?|half([1-4](x[1-4])?)?|int([1-4](x[1-4])?)?|matrix|min10float([1-4](x[1-4])?)?|min12int([1-4](x[1-4])?)?|min16float([1-4](x[1-4])?)?|min16int([1-4](x[1-4])?)?|min16uint([1-4](x[1-4])?)?|unsigned|uint([1-4](x[1-4])?)?|vector|void)\\b",name:"storage.type.basic.hlsl"},{match:"\\b([a-zA-Z_][a-zA-Z0-9_]*)(?=[\\s]*\\()",name:"support.function.hlsl"},{match:"(?<=\\:\\s|\\:)(?i:BINORMAL[0-9]*|BLENDINDICES[0-9]*|BLENDWEIGHT[0-9]*|COLOR[0-9]*|NORMAL[0-9]*|POSITIONT|POSITION|PSIZE[0-9]*|TANGENT[0-9]*|TEXCOORD[0-9]*|FOG|TESSFACTOR[0-9]*|VFACE|VPOS|DEPTH[0-9]*)\\b",name:"support.variable.semantic.hlsl"},{match:"(?<=\\:\\s|\\:)(?i:SV_ClipDistance[0-9]*|SV_CullDistance[0-9]*|SV_Coverage|SV_Depth|SV_DepthGreaterEqual[0-9]*|SV_DepthLessEqual[0-9]*|SV_InstanceID|SV_IsFrontFace|SV_Position|SV_RenderTargetArrayIndex|SV_SampleIndex|SV_StencilRef|SV_Target[0-7]?|SV_VertexID|SV_ViewportArrayIndex)\\b",name:"support.variable.semantic.sm4.hlsl"},{match:"(?<=\\:\\s|\\:)(?i:SV_DispatchThreadID|SV_DomainLocation|SV_GroupID|SV_GroupIndex|SV_GroupThreadID|SV_GSInstanceID|SV_InsideTessFactor|SV_OutputControlPointID|SV_TessFactor)\\b",name:"support.variable.semantic.sm5.hlsl"},{match:"(?<=\\:\\s|\\:)(?i:SV_InnerCoverage|SV_StencilRef)\\b",name:"support.variable.semantic.sm5_1.hlsl"},{match:"\\b(column_major|const|export|extern|globallycoherent|groupshared|inline|inout|in|out|precise|row_major|shared|static|uniform|volatile)\\b",name:"storage.modifier.hlsl"},{match:"\\b(snorm|unorm)\\b",name:"storage.modifier.float.hlsl"},{match:"\\b(packoffset|register)\\b",name:"storage.modifier.postfix.hlsl"},{match:"\\b(centroid|linear|nointerpolation|noperspective|sample)\\b",name:"storage.modifier.interpolation.hlsl"},{match:"\\b(lineadj|line|point|triangle|triangleadj)\\b",name:"storage.modifier.geometryshader.hlsl"},{match:"\\b(string)\\b",name:"support.type.other.hlsl"},{match:"\\b(AppendStructuredBuffer|Buffer|ByteAddressBuffer|ConstantBuffer|ConsumeStructuredBuffer|InputPatch|OutputPatch)\\b",name:"support.type.object.hlsl"},{match:"\\b(RasterizerOrderedBuffer|RasterizerOrderedByteAddressBuffer|RasterizerOrderedStructuredBuffer|RasterizerOrderedTexture1D|RasterizerOrderedTexture1DArray|RasterizerOrderedTexture2D|RasterizerOrderedTexture2DArray|RasterizerOrderedTexture3D)\\b",name:"support.type.object.rasterizerordered.hlsl"},{match:"\\b(RWBuffer|RWByteAddressBuffer|RWStructuredBuffer|RWTexture1D|RWTexture1DArray|RWTexture2D|RWTexture2DArray|RWTexture3D)\\b",name:"support.type.object.rw.hlsl"},{match:"\\b(LineStream|PointStream|TriangleStream)\\b",name:"support.type.object.geometryshader.hlsl"},{match:"\\b(sampler|sampler1D|sampler2D|sampler3D|samplerCUBE|sampler_state)\\b",name:"support.type.sampler.legacy.hlsl"},{match:"\\b(SamplerState|SamplerComparisonState)\\b",name:"support.type.sampler.hlsl"},{match:"\\b(texture2D|textureCUBE)\\b",name:"support.type.texture.legacy.hlsl"},{match:"\\b(Texture1D|Texture1DArray|Texture2D|Texture2DArray|Texture2DMS|Texture2DMSArray|Texture3D|TextureCube|TextureCubeArray)\\b",name:"support.type.texture.hlsl"},{match:"\\b(cbuffer|class|interface|namespace|struct|tbuffer)\\b",name:"storage.type.structured.hlsl"},{match:"\\b(FALSE|TRUE|NULL)\\b",name:"support.constant.property-value.fx.hlsl"},{match:"\\b(BlendState|DepthStencilState|RasterizerState)\\b",name:"support.type.fx.hlsl"},{match:"\\b(technique|Technique|technique10|technique11|pass)\\b",name:"storage.type.fx.technique.hlsl"},{match:"\\b(AlphaToCoverageEnable|BlendEnable|SrcBlend|DestBlend|BlendOp|SrcBlendAlpha|DestBlendAlpha|BlendOpAlpha|RenderTargetWriteMask)\\b",name:"meta.object-literal.key.fx.blendstate.hlsl"},{match:"\\b(DepthEnable|DepthWriteMask|DepthFunc|StencilEnable|StencilReadMask|StencilWriteMask|FrontFaceStencilFail|FrontFaceStencilZFail|FrontFaceStencilPass|FrontFaceStencilFunc|BackFaceStencilFail|BackFaceStencilZFail|BackFaceStencilPass|BackFaceStencilFunc)\\b",name:"meta.object-literal.key.fx.depthstencilstate.hlsl"},{match:"\\b(FillMode|CullMode|FrontCounterClockwise|DepthBias|DepthBiasClamp|SlopeScaleDepthBias|ZClipEnable|ScissorEnable|MultiSampleEnable|AntiAliasedLineEnable)\\b",name:"meta.object-literal.key.fx.rasterizerstate.hlsl"},{match:"\\b(Filter|AddressU|AddressV|AddressW|MipLODBias|MaxAnisotropy|ComparisonFunc|BorderColor|MinLOD|MaxLOD)\\b",name:"meta.object-literal.key.fx.samplerstate.hlsl"},{match:"\\b(?i:ZERO|ONE|SRC_COLOR|INV_SRC_COLOR|SRC_ALPHA|INV_SRC_ALPHA|DEST_ALPHA|INV_DEST_ALPHA|DEST_COLOR|INV_DEST_COLOR|SRC_ALPHA_SAT|BLEND_FACTOR|INV_BLEND_FACTOR|SRC1_COLOR|INV_SRC1_COLOR|SRC1_ALPHA|INV_SRC1_ALPHA)\\b",name:"support.constant.property-value.fx.blend.hlsl"},{match:"\\b(?i:ADD|SUBTRACT|REV_SUBTRACT|MIN|MAX)\\b",name:"support.constant.property-value.fx.blendop.hlsl"},{match:"\\b(?i:ALL)\\b",name:"support.constant.property-value.fx.depthwritemask.hlsl"},{match:"\\b(?i:NEVER|LESS|EQUAL|LESS_EQUAL|GREATER|NOT_EQUAL|GREATER_EQUAL|ALWAYS)\\b",name:"support.constant.property-value.fx.comparisonfunc.hlsl"},{match:"\\b(?i:KEEP|REPLACE|INCR_SAT|DECR_SAT|INVERT|INCR|DECR)\\b",name:"support.constant.property-value.fx.stencilop.hlsl"},{match:"\\b(?i:WIREFRAME|SOLID)\\b",name:"support.constant.property-value.fx.fillmode.hlsl"},{match:"\\b(?i:NONE|FRONT|BACK)\\b",name:"support.constant.property-value.fx.cullmode.hlsl"},{match:"\\b(?i:MIN_MAG_MIP_POINT|MIN_MAG_POINT_MIP_LINEAR|MIN_POINT_MAG_LINEAR_MIP_POINT|MIN_POINT_MAG_MIP_LINEAR|MIN_LINEAR_MAG_MIP_POINT|MIN_LINEAR_MAG_POINT_MIP_LINEAR|MIN_MAG_LINEAR_MIP_POINT|MIN_MAG_MIP_LINEAR|ANISOTROPIC|COMPARISON_MIN_MAG_MIP_POINT|COMPARISON_MIN_MAG_POINT_MIP_LINEAR|COMPARISON_MIN_POINT_MAG_LINEAR_MIP_POINT|COMPARISON_MIN_POINT_MAG_MIP_LINEAR|COMPARISON_MIN_LINEAR_MAG_MIP_POINT|COMPARISON_MIN_LINEAR_MAG_POINT_MIP_LINEAR|COMPARISON_MIN_MAG_LINEAR_MIP_POINT|COMPARISON_MIN_MAG_MIP_LINEAR|COMPARISON_ANISOTROPIC|TEXT_1BIT)\\b",name:"support.constant.property-value.fx.filter.hlsl"},{match:"\\b(?i:WRAP|MIRROR|CLAMP|BORDER|MIRROR_ONCE)\\b",name:"support.constant.property-value.fx.textureaddressmode.hlsl"},{begin:'"',end:'"',name:"string.quoted.double.hlsl",patterns:[{match:"\\\\.",name:"constant.character.escape.hlsl"}]}],scopeName:"source.hlsl"})]},4531:(e,a,t)=>{t.r(a),t.d(a,{default:()=>s});var r=t(976);const n=Object.freeze({displayName:"ShaderLab",name:"shaderlab",patterns:[{begin:"//",end:"$",name:"comment.line.double-slash.shaderlab"},{match:"\\b(?i:Range|Float|Int|Color|Vector|2D|3D|Cube|Any)\\b",name:"support.type.basic.shaderlab"},{include:"#numbers"},{match:"\\b(?i:Shader|Properties|SubShader|Pass|Category)\\b",name:"storage.type.structure.shaderlab"},{match:"\\b(?i:Name|Tags|Fallback|CustomEditor|Cull|ZWrite|ZTest|Offset|Blend|BlendOp|ColorMask|AlphaToMask|LOD|Lighting|Stencil|Ref|ReadMask|WriteMask|Comp|CompBack|CompFront|Fail|ZFail|UsePass|GrabPass|Dependency|Material|Diffuse|Ambient|Shininess|Specular|Emission|Fog|Mode|Density|SeparateSpecular|SetTexture|Combine|ConstantColor|Matrix|AlphaTest|ColorMaterial|BindChannels|Bind)\\b",name:"support.type.propertyname.shaderlab"},{match:"\\b(?i:Back|Front|On|Off|[RGBA]{1,3}|AmbientAndDiffuse|Emission)\\b",name:"support.constant.property-value.shaderlab"},{match:"\\b(?i:Less|Greater|LEqual|GEqual|Equal|NotEqual|Always|Never)\\b",name:"support.constant.property-value.comparisonfunction.shaderlab"},{match:"\\b(?i:Keep|Zero|Replace|IncrSat|DecrSat|Invert|IncrWrap|DecrWrap)\\b",name:"support.constant.property-value.stenciloperation.shaderlab"},{match:"\\b(?i:Previous|Primary|Texture|Constant|Lerp|Double|Quad|Alpha)\\b",name:"support.constant.property-value.texturecombiners.shaderlab"},{match:"\\b(?i:Global|Linear|Exp2|Exp)\\b",name:"support.constant.property-value.fog.shaderlab"},{match:"\\b(?i:Vertex|Normal|Tangent|TexCoord0|TexCoord1)\\b",name:"support.constant.property-value.bindchannels.shaderlab"},{match:"\\b(?i:Add|Sub|RevSub|Min|Max|LogicalClear|LogicalSet|LogicalCopyInverted|LogicalCopy|LogicalNoop|LogicalInvert|LogicalAnd|LogicalNand|LogicalOr|LogicalNor|LogicalXor|LogicalEquiv|LogicalAndReverse|LogicalAndInverted|LogicalOrReverse|LogicalOrInverted)\\b",name:"support.constant.property-value.blendoperations.shaderlab"},{match:"\\b(?i:One|Zero|SrcColor|SrcAlpha|DstColor|DstAlpha|OneMinusSrcColor|OneMinusSrcAlpha|OneMinusDstColor|OneMinusDstAlpha)\\b",name:"support.constant.property-value.blendfactors.shaderlab"},{match:'\\[([a-zA-Z_][a-zA-Z0-9_]*)\\](?!\\s*[a-zA-Z_][a-zA-Z0-9_]*\\s*\\(")',name:"support.variable.reference.shaderlab"},{begin:"(\\[)",end:"(\\])",name:"meta.attribute.shaderlab",patterns:[{match:"\\G([a-zA-Z]+)\\b",name:"support.type.attributename.shaderlab"},{include:"#numbers"}]},{match:"\\b([a-zA-Z_][a-zA-Z0-9_]*)\\s*\\(",name:"support.variable.declaration.shaderlab"},{begin:"\\b(CGPROGRAM|CGINCLUDE)\\b",beginCaptures:{1:{name:"keyword.other"}},end:"\\b(ENDCG)\\b",endCaptures:{1:{name:"keyword.other"}},name:"meta.cgblock",patterns:[{include:"#hlsl-embedded"}]},{begin:"\\b(HLSLPROGRAM|HLSLINCLUDE)\\b",beginCaptures:{1:{name:"keyword.other"}},end:"\\b(ENDHLSL)\\b",endCaptures:{1:{name:"keyword.other"}},name:"meta.hlslblock",patterns:[{include:"#hlsl-embedded"}]},{begin:'"',end:'"',name:"string.quoted.double.shaderlab"}],repository:{"hlsl-embedded":{patterns:[{include:"source.hlsl"},{match:"\\b(fixed([1-4](x[1-4])?)?)\\b",name:"storage.type.basic.shaderlab"},{match:"\\b(UNITY_MATRIX_MVP|UNITY_MATRIX_MV|UNITY_MATRIX_M|UNITY_MATRIX_V|UNITY_MATRIX_P|UNITY_MATRIX_VP|UNITY_MATRIX_T_MV|UNITY_MATRIX_I_V|UNITY_MATRIX_IT_MV|_Object2World|_World2Object|unity_ObjectToWorld|unity_WorldToObject)\\b",name:"support.variable.transformations.shaderlab"},{match:"\\b(_WorldSpaceCameraPos|_ProjectionParams|_ScreenParams|_ZBufferParams|unity_OrthoParams|unity_CameraProjection|unity_CameraInvProjection|unity_CameraWorldClipPlanes)\\b",name:"support.variable.camera.shaderlab"},{match:"\\b(_Time|_SinTime|_CosTime|unity_DeltaTime)\\b",name:"support.variable.time.shaderlab"},{match:"\\b(_LightColor0|_WorldSpaceLightPos0|_LightMatrix0|unity_4LightPosX0|unity_4LightPosY0|unity_4LightPosZ0|unity_4LightAtten0|unity_LightColor|_LightColor|unity_LightPosition|unity_LightAtten|unity_SpotDirection)\\b",name:"support.variable.lighting.shaderlab"},{match:"\\b(unity_AmbientSky|unity_AmbientEquator|unity_AmbientGround|UNITY_LIGHTMODEL_AMBIENT|unity_FogColor|unity_FogParams)\\b",name:"support.variable.fog.shaderlab"},{match:"\\b(unity_LODFade)\\b",name:"support.variable.various.shaderlab"},{match:"\\b(SHADER_API_D3D9|SHADER_API_D3D11|SHADER_API_GLCORE|SHADER_API_OPENGL|SHADER_API_GLES|SHADER_API_GLES3|SHADER_API_METAL|SHADER_API_D3D11_9X|SHADER_API_PSSL|SHADER_API_XBOXONE|SHADER_API_PSP2|SHADER_API_WIIU|SHADER_API_MOBILE|SHADER_API_GLSL)\\b",name:"support.variable.preprocessor.targetplatform.shaderlab"},{match:"\\b(SHADER_TARGET)\\b",name:"support.variable.preprocessor.targetmodel.shaderlab"},{match:"\\b(UNITY_VERSION)\\b",name:"support.variable.preprocessor.unityversion.shaderlab"},{match:"\\b(UNITY_BRANCH|UNITY_FLATTEN|UNITY_NO_SCREENSPACE_SHADOWS|UNITY_NO_LINEAR_COLORSPACE|UNITY_NO_RGBM|UNITY_NO_DXT5nm|UNITY_FRAMEBUFFER_FETCH_AVAILABLE|UNITY_USE_RGBA_FOR_POINT_SHADOWS|UNITY_ATTEN_CHANNEL|UNITY_HALF_TEXEL_OFFSET|UNITY_UV_STARTS_AT_TOP|UNITY_MIGHT_NOT_HAVE_DEPTH_Texture|UNITY_NEAR_CLIP_VALUE|UNITY_VPOS_TYPE|UNITY_CAN_COMPILE_TESSELLATION|UNITY_COMPILER_HLSL|UNITY_COMPILER_HLSL2GLSL|UNITY_COMPILER_CG|UNITY_REVERSED_Z)\\b",name:"support.variable.preprocessor.platformdifference.shaderlab"},{match:"\\b(UNITY_PASS_FORWARDBASE|UNITY_PASS_FORWARDADD|UNITY_PASS_DEFERRED|UNITY_PASS_SHADOWCASTER|UNITY_PASS_PREPASSBASE|UNITY_PASS_PREPASSFINAL)\\b",name:"support.variable.preprocessor.texture2D.shaderlab"},{match:"\\b(appdata_base|appdata_tan|appdata_full|appdata_img)\\b",name:"support.class.structures.shaderlab"},{match:"\\b(SurfaceOutputStandardSpecular|SurfaceOutputStandard|SurfaceOutput|Input)\\b",name:"support.class.surface.shaderlab"}]},numbers:{patterns:[{match:"\\b([0-9]+\\.?[0-9]*)\\b",name:"constant.numeric.shaderlab"}]}},scopeName:"source.shaderlab",embeddedLangs:["hlsl"],aliases:["shader"]});var s=[...r.default,n]}}]);