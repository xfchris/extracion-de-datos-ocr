from .principal_function_ocr import label_detection_ocr
from .identify_acts import IdentifyDocumentActs
from .identify_value_acts import value_of_acts
from .acts_without_amount import act_filter_without_amount
from .acts_value_vectors import AppendProcessActsVector
from .return_acts_json_values import return_acts_target
from .basic_operation_into_a_list import BasicListOperation
from .code_acts_class import CodeActs
from .identification_into_scripture import return_identification_documents
from .money_value_all_acts import acts_without_value_money
import json
import os
import glob
import os.path as path


def delete_static_files(directory):
    files = glob.glob(directory)
    for f in files:
        os.remove(f)


def value_acts(filename, pdf_size, options):
    current_directory = path.abspath(path.join(os.getcwd(), '.'))
    media_directory = os.path.join(
        os.path.sep, current_directory, 'media', 'static', 'output', '', '*')
    uploads_directory = os.path.join(
        os.path.sep, current_directory, 'media', 'uploads', '', '*')
    resources_directory = os.path.join(
        os.path.sep, current_directory, 'resources', '')
    delete_static_files(media_directory)
    
    ocr_text = label_detection_ocr(filename, pdf_size, options)
    
    if options <= 1:
        position_acts = []
        index_vector_acts = []
        code_concatenated = []
        concatenated_vectors = []
        code_return = []
        value_without_amount_return = []
        vector_code_without_repeat = []
        with open(resources_directory + 'acts_without_amount.json', 'r') as acts_wa:
            vector_acts_without_amount = json.loads(acts_wa.read())
        with open(resources_directory + 'acts_with_amount.json', 'r') as acts_a:
            vector_acts = json.loads(acts_a.read())
        with open(resources_directory + 'target_names.json', 'r') as target_id:
            target = json.loads(target_id.read())
        with open(resources_directory + 'name_extract.json', 'r') as identify:
            targets_names = json.loads(identify.read())
        with open(resources_directory + 'identificators.json', 'r') as labels:
            target_identify = json.loads(labels.read())
        with open(resources_directory + 'delete_words.json', 'r') as file_type:
            constant_target = json.loads(file_type.read())

        vector_all_acts = AppendProcessActsVector(
            concatenated_vectors, code_concatenated)
        vector_all_acts_lower_case = vector_all_acts.lower_case_all_acts_vector(
            vector_acts_without_amount, vector_acts, 1)[0]
        code_acts_verification = vector_all_acts.lower_case_all_acts_vector(
            vector_acts_without_amount, vector_acts, 1)[-1]
        document_acts = IdentifyDocumentActs(position_acts, index_vector_acts)
        code_acts = document_acts.extract_acts(
            ocr_text, vector_all_acts_lower_case)[-1]
        name_detected = document_acts.extract_names(
            ocr_text, targets_names['target_name'])
        non_repeated_code = BasicListOperation(
            code_acts, vector_code_without_repeat)
        code_detected_acts = non_repeated_code.non_repeated_acts()
        number_value_acts = value_of_acts(ocr_text, vector_all_acts_lower_case)
        return_code_acts = CodeActs(
            code_detected_acts, code_acts_verification, code_return, value_without_amount_return)
        format_code = return_code_acts.extract_codes()
        acts_scripture_return = return_acts_target(
            vector_all_acts_lower_case, code_detected_acts)
        acts_without_cost_lower_case = vector_all_acts.lower_case_vector(
            vector_acts_without_amount['nombre'])
        numerical_data = acts_without_value_money(
            acts_scripture_return, acts_without_cost_lower_case, number_value_acts[0])
        identification_number = return_identification_documents(
            ocr_text, target['targets'])
        format_code_acts = tuple(map(lambda x: int(x), tuple(format_code)))
        extract_number = non_repeated_code.isempty_list(number_value_acts[0])
        document_type = document_acts.extract_tittle(ocr_text)
        upper_limit = document_acts.extract_identificators(
            ocr_text, target_identify['name_identificators'])
        number_id, inferior_limit = document_acts.inferior_limit(
            upper_limit, ocr_text)
        names = document_acts.extract_names_with_upper_inferior_limits(ocr_text, upper_limit, inferior_limit,
                                                                       target['targets'],
                                                                       target_identify['name_identificators'],
                                                                       constant_target=constant_target['identificators'])
        id_number = document_acts.identification_number_with_upper_inferior_limits(
            ocr_text, upper_limit, inferior_limit)
        business_type = document_acts.business_type_upper_limit_inferior_limit(ocr_text, upper_limit, inferior_limit,
                                                                               target['targets'])

        structured_data = {'acts': acts_scripture_return, 'code_acts': format_code_acts, 'value_acts': numerical_data,
                           'document_type': document_type, 'names': names, 'identification_number': id_number,
                           'business_type': business_type, 'ocr_text': ocr_text}
    else:
        structured_data = {'ocr_text': ocr_text}

    delete_static_files(uploads_directory)
    delete_static_files(media_directory)
    return json.dumps(structured_data)
