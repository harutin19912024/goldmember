<?php

namespace app\components;

use yii\base\Component;

class DateArm extends Component
{

    /**
     * Այս ֆունկցիան դզեվափոխում ժամանակը Հայկական տոմարով և վերադարցնում է այն
     * @param array $dataProduct 
     * @return array վերադարցնում է Մասսիվ
     * */
    public static function getArmDate($dataProduct)
    {

        if(is_array($dataProduct)) {
            foreach ($dataProduct as $data) {
                /*\Yii::$app->formatter->locale = 'hy-HY';
                $seg=\Yii::$app->formatter->asDate($segments[0], 'long');*/

                //explode to ' '
                $segments = explode(' ', $data->date);

                //explode to '-'
                $seg = explode('-', $segments[0]);

                switch ($seg[1]) {
                    case '01':
                        $seg[1] = 'Հունվար';
                        break;
                    case '02':
                        $seg[1] = 'Փետրվար';
                        break;
                    case '03':
                        $seg[1] = 'Մարտ';
                        break;
                    case '04':
                        $seg[1] = 'Ապրիլ';
                        break;
                    case '05':
                        $seg[1] = 'Մայիս';
                        break;
                    case '06':
                        $seg[1] = 'Հունիս';
                        break;
                    case '07':
                        $seg[1] = 'Հուլիս';
                        break;
                    case '08':
                        $seg[1] = 'Օգոստոս';
                        break;
                    case '09':
                        $seg[1] = 'Սեպտեմբեր';
                        break;
                    case '10':
                        $seg[1] = 'Հոկտեմբեր';
                        break;
                    case '11':
                        $seg[1] = 'Նոյեմբեր';
                        break;
                    case '12':
                        $seg[1] = 'Դեկտեմբեր';
                        break;
                    default:
                        continue;
                }


                //переименоваем
                $data->date = $seg;


            }
        }else
        {

            //explode to ' '
            $segments = explode(' ', $dataProduct);

            //explode to '-'
            $seg = explode('-', $segments[0]);

            switch ($seg[1]) {
                case '01':
                    $seg[1] = 'Հունվար';
                    break;
                case '02':
                    $seg[1] = 'Փետրվար';
                    break;
                case '03':
                    $seg[1] = 'Մարտ';
                    break;
                case '04':
                    $seg[1] = 'Ապրիլ';
                    break;
                case '05':
                    $seg[1] = 'Մայիս';
                    break;
                case '06':
                    $seg[1] = 'Հունիս';
                    break;
                case '07':
                    $seg[1] = 'Հուլիս';
                    break;
                case '08':
                    $seg[1] = 'Օգոստոս';
                    break;
                case '09':
                    $seg[1] = 'Սեպտեմբեր';
                    break;
                case '10':
                    $seg[1] = 'Հոկտեմբեր';
                    break;
                case '11':
                    $seg[1] = 'Նոյեմբեր';
                    break;
                case '12':
                    $seg[1] = 'Դեկտեմբեր';
                    break;
                default:
                    continue;
            }



            //переименоваем
            $dataProduct = $seg;


        }
        //return 
        return $dataProduct;
    }

    public static function getDate($data)
    {
        //explode to ' '
        $segments = explode(' ', $data);
        \Yii::$app->formatter->locale = 'hy-HY';
        return  \Yii::$app->formatter->asDate($segments[0], 'long');
        
    }
}