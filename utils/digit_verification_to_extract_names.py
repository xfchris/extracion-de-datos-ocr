
def digit_verification(possible_digit, ocr_text, index_word):
    next_position = 0
    constant_position = 0
    while not possible_digit:
        if index_word == len(ocr_text)-1:
            next_position = 0
            break
        index_word += 1
        constant_position += 1
        next_position = ocr_text[index_word]
        next_position = next_position.replace('-', '').replace('.', '')
        possible_digit = next_position.isdigit()
    return next_position, index_word