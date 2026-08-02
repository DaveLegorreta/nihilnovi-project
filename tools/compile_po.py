#!/usr/bin/env python3
"""
Compila nihilnovi-en_US.po a nihilnovi-en_US.mo
"""
import struct
import os

def compile_po_to_mo(po_path, mo_path):
    """Compila un archivo .po a .mo básico."""
    
    # Leer el archivo .po
    with open(po_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Parsear entradas msgid/msgstr
    entries = []
    current_msgid = None
    current_msgstr = None
    
    lines = content.split('\n')
    i = 0
    while i < len(lines):
        line = lines[i].strip()
        
        if line.startswith('msgid '):
            # Guardar entrada anterior si existe
            if current_msgid is not None and current_msgstr is not None:
                entries.append((current_msgid, current_msgstr))
            
            # Extraer msgid
            current_msgid = line[6:].strip().strip('"')
            current_msgstr = ''
            
            # Leer líneas continuas del msgid
            i += 1
            while i < len(lines) and lines[i].strip().startswith('"'):
                current_msgid += lines[i].strip().strip('"')
                i += 1
            continue
            
        elif line.startswith('msgstr '):
            # Extraer msgstr
            current_msgstr = line[7:].strip().strip('"')
            
            # Leer líneas continuas del msgstr
            i += 1
            while i < len(lines) and lines[i].strip().startswith('"'):
                current_msgstr += lines[i].strip().strip('"')
                i += 1
            continue
            
        i += 1
    
    # Guardar última entrada
    if current_msgid is not None and current_msgstr is not None:
        entries.append((current_msgid, current_msgstr))
    
    # Filtrar entradas vacías y header
    entries = [(k, v) for k, v in entries if k != '']
    
    if not entries:
        print("No se encontraron entradas para compilar")
        return False
    
    # Generar archivo .mo (formato GNU gettext)
    # https://www.gnu.org/software/gettext/manual/html_node/MO-Files.html
    
    # Ordenar entradas
    entries.sort(key=lambda x: x[0])
    
    n = len(entries)
    
    # Calcular offsets
    header_size = 28
    table_size = 8 * n  # cada entrada: 4 bytes length + 4 bytes offset
    
    o = header_size  # Offset de tabla de strings originales
    t = o + table_size  # Offset de tabla de strings traducidos
    s = t + table_size  # Offset de datos de strings
    
    # Construir tablas y datos
    id_table = b''
    str_table = b''
    string_data = b''
    
    for msgid, msgstr in entries:
        id_bytes = msgid.encode('utf-8')
        str_bytes = msgstr.encode('utf-8')
        
        id_offset = len(string_data)
        str_offset = len(string_data) + len(id_bytes) + 1
        
        id_table += struct.pack('II', len(id_bytes), id_offset)
        str_table += struct.pack('II', len(str_bytes), str_offset)
        string_data += id_bytes + b'\x00' + str_bytes + b'\x00'
    
    # Header del .mo (7 campos de 4 bytes cada uno = 28 bytes)
    # Magic number, Version, n, o, t, hash_size, hash_offset
    header = struct.pack('IIIIIII',
        0x950412de,  # Magic number
        0,           # Version
        n,           # Number of strings
        o,           # Offset of original strings table
        t,           # Offset of translated strings table
        0,           # Size of hashing table
        s,           # Offset of hashing table
    )
    
    # Escribir archivo .mo
    with open(mo_path, 'wb') as f:
        f.write(header)
        f.write(id_table)
        f.write(str_table)
        f.write(string_data)
    
    print(f"Compilado: {mo_path}")
    print(f"Entradas: {n}")
    return True

if __name__ == '__main__':
    base_dir = 'c:/Users/david/OneDrive/Documentos/Skills/Projecto MKT/nihilnovi-theme/languages'
    po_path = os.path.join(base_dir, 'nihilnovi-en_US.po')
    mo_path = os.path.join(base_dir, 'nihilnovi-en_US.mo')
    
    if compile_po_to_mo(po_path, mo_path):
        print("Compilación exitosa")
    else:
        print("Error en compilación")
