import re

with open('data/nihilnovi_articles_import.xml', 'r', encoding='utf-8') as f:
    content = f.read()

# Define article assignments based on the analysis
# Format: {meta_value_pattern: [categories_to_add]}
article_assignments = {
    # MÓDULO I — Presocráticos
    'FIL_01_A': ['presocraticos', 'epistemologia'],
    'FIL_01_B': ['presocraticos', 'metafisica'],
    'FIL_01_C': ['presocraticos', 'metafisica'],
    'FIL_01_D': ['presocraticos', 'metafisica'],
    'FIL_01_E': ['presocraticos', 'metafisica'],
    'FIL_01_F': ['presocraticos', 'metafisica'],
    'FIL_01_G': ['presocraticos', 'metafisica'],
    'FIL_01_H': ['presocraticos', 'logica'],
    'FIL_01_I': ['presocraticos', 'metafisica'],
    'FIL_01_J': ['presocraticos', 'metafisica'],
    'FIL_01_K': ['presocraticos', 'metafisica'],
    'FIL_01_L': ['presocraticos', 'epistemologia'],
    # MÓDULO II — Sócrates
    'FIL_02_A': ['clasicos', 'epistemologia'],
    # MÓDULO III — Platón
    'FIL_03_01': ['clasicos', 'etica'],
    'FIL_03_02': ['clasicos', 'etica', 'politica'],
    'FIL_03_03': ['clasicos', 'politica'],
    'FIL_03_04': ['clasicos', 'etica'],
    'FIL_03_05': ['clasicos', 'etica'],
    'FIL_03_06': ['clasicos', 'etica'],
    'FIL_03_07': ['clasicos', 'etica'],
    'FIL_03_08': ['clasicos', 'etica', 'epistemologia'],
    'FIL_03_09': ['clasicos', 'estetica'],
    'FIL_03_10': ['clasicos', 'estetica'],
    'FIL_03_14': ['clasicos', 'metafisica', 'logica'],
    'FIL_03_15': ['clasicos', 'politica'],
    'FIL_03_16': ['clasicos', 'metafisica'],
    'FIL_03_17': ['clasicos', 'politica'],
    # MÓDULO IV — Aristóteles Política
    'FIL_04_A': ['clasicos', 'politica'],
    'FIL_04_01': ['clasicos', 'politica'],
    'FIL_04_02': ['clasicos', 'politica'],
    'FIL_04_03': ['clasicos', 'politica'],
    'FIL_04_04': ['clasicos', 'politica'],
    'FIL_04_05': ['clasicos', 'politica'],
    'FIL_04_06': ['clasicos', 'politica'],
    'FIL_04_07': ['clasicos', 'politica'],
    'FIL_04_08': ['clasicos', 'politica', 'estetica'],
    # MÓDULO IV — Aristóteles Metafísica
    'FIL_04_MET_01': ['clasicos', 'metafisica'],
    'FIL_04_MET_02': ['clasicos', 'metafisica', 'epistemologia'],
    'FIL_04_MET_03': ['clasicos', 'metafisica', 'logica'],
    'FIL_04_MET_04': ['clasicos', 'metafisica', 'logica'],
    'FIL_04_MET_05': ['clasicos', 'metafisica', 'logica'],
}

# Category name mapping
cat_names = {
    'presocraticos': 'Presocráticos',
    'clasicos': 'Clásicos',
    'metafisica': 'Metafísica',
    'etica': 'Ética',
    'politica': 'Política',
    'epistemologia': 'Epistemología',
    'logica': 'Lógica',
    'estetica': 'Estética',
}

# Find all items
items = re.findall(r'<item>.*?</item>', content, re.DOTALL)
print(f'Total items found: {len(items)}')

modified_count = 0

for item in items:
    # Get meta_value (source filename)
    meta_match = re.search(r'<wp:meta_value><!\[CDATA\[(FIL_[^\]]+)\]\]></wp:meta_value>', item)
    if not meta_match:
        continue
    
    meta_value = meta_match.group(1).upper().replace('.MD', '')
    
    # Find matching assignment
    matched_categories = None
    for pattern, categories in article_assignments.items():
        if meta_value == pattern.upper():
            matched_categories = categories
            break
    
    if not matched_categories:
        print(f'No assignment found for: {meta_value}')
        continue
    
    # Build category XML to insert
    category_xml = ''
    for cat in matched_categories:
        cat_name = cat_names.get(cat, cat)
        category_xml += f'      <category domain="category" nicename="{cat}"><![CDATA[{cat_name}]]></category>\n'
    
    # Find the existing filosofia category in this item
    old_cat_pattern = r'<category domain="category" nicename="filosofia">.*?<\/category>'
    old_cat_match = re.search(old_cat_pattern, item)
    
    if old_cat_match:
        old_cat = old_cat_match.group(0)
        new_item = item.replace(old_cat, old_cat + '\n' + category_xml)
        content = content.replace(item, new_item)
        modified_count += 1
        print(f'Modified: {meta_value}')
    else:
        print(f'No filosofia category found in: {meta_value}')

print(f'\nTotal items modified: {modified_count}')

# Save
with open('data/nihilnovi_articles_import.xml', 'w', encoding='utf-8') as f:
    f.write(content)

print('File saved successfully')
