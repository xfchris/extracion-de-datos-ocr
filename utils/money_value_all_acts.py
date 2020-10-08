
def acts_without_value_money(detected_acts, vector_acts_without_value, numerical_value):
    intersection = set(detected_acts) & set(vector_acts_without_value)
    list_intersection = [x_index for x_index in iter(intersection)]
    if len(list_intersection) != 0:
        cte_nan = len(list_intersection) * 'nan,'
        numerical_value.extend(cte_nan.split(','))
        numerical_result = [x for x in numerical_value if x]
    else:
        numerical_result = numerical_value
    return numerical_result




