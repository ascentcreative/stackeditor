{{-- @includeFirst(['stackeditor.block.' . $type . '.edit', 'cms::stackeditor.block.' . $type . '.edit', 'stackeditor::block.' . $type . '.edit', 'stackeditor::block.missing'], ['type'=>$type]) --}}
@includeFirst( array_merge( stackeditorBladePaths($type, 'edit'), ['stackeditor::block.missing']), ['type'=>$type])
{{ $slot }}