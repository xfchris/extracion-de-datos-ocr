

def digit_verification_ocr_text(possible_digit, ocr_text, index_word):
    next_position = 0
    acum_constant_position = 0
    numerical_find_number = 5
    while not possible_digit:
        if index_word == len(ocr_text)-1:
            next_position = 0
            break
        index_word += 1
        acum_constant_position += 1
        next_position = ocr_text[index_word]
        next_position = next_position.replace('-', '').replace(',', '')
        possible_digit = next_position.isdigit()
        if acum_constant_position == numerical_find_number:
            break
    return next_position, index_word
