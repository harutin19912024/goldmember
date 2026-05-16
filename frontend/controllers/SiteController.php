<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\Product;
use frontend\models\Customer;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\DonateForm;
use backend\models\TrProduct;
use backend\models\ProductImage;
use common\models\User;
use common\models\Language;
use backend\models\Pages;
use backend\models\Aboutus;
use backend\models\Sitesettings;
use backend\models\News;
use backend\models\NewsCategory;
use backend\models\MetalPrices;
use backend\models\ExchangeRates;
use common\models\MetalPriceReal;
use common\models\Favorites;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        // if (!Yii::$app->user->isGuest) {
        //     $user = Yii::$app->user->identity;
        //     $user->last_activity = date('Y-m-d H:i:s');
        //     $user->save(false, ['last_activity']);
        // }

        if (!parent::beforeAction($action)) {
            return false;
        }
    
        $language = Yii::$app->language;
        if (!$language || $language === '') {
            Yii::$app->language = '';
            return $this->redirect('/en');
        }
        
         $settings = Sitesettings::find()->one();
            if ($settings && $settings->maintenance_mode == 1 && Yii::$app->controller->action->id != 'maintenance') {
                return Yii::$app->response->redirect(['/site/maintenance']);
            }
    
        return true;
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $db = Yii::$app->db;

        // Active sessions in the last 5 minutes (guests + logged-in)
        $onlineNow = (int) $db->createCommand(
            'SELECT COUNT(*) FROM session WHERE last_write > :since',
            [':since' => time() - 300]
        )->queryScalar();

        // Registered non-admin users
        $registeredUsers = (int) $db->createCommand(
            'SELECT COUNT(*) FROM user WHERE role != 0'
        )->queryScalar();

        // Distinct visitors today
        $todaysVisits = (int) $db->createCommand(
            'SELECT COUNT(DISTINCT session_id) FROM user_activity WHERE DATE(last_activity) = CURDATE()'
        )->queryScalar();

        // All-time distinct visitors
        $totalVisits = (int) $db->createCommand(
            'SELECT COUNT(DISTINCT session_id) FROM user_activity'
        )->queryScalar();

        return $this->render('index', [
            'onlineNow'       => $onlineNow,
            'registeredUsers' => $registeredUsers,
            'todaysVisits'    => $todaysVisits,
            'totalVisits'     => $totalVisits,
        ]);
    }
    
    public function actionMaintenance()
    {
        $settings = Sitesettings::find()->one();
        if ($settings && $settings->maintenance_mode != 1 && Yii::$app->controller->action->id == 'maintenance') {
            return Yii::$app->response->redirect(['/site/index']);
        }
        return $this->render('maintenance');
    }
    
    public function actionAgoraMeeting()
    {

        return $this->render('agora');
    }

    public function actionDonate()
    {
        $model = new DonateForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->donate()) {
                Yii::$app->session->setFlash('success', 'Thank you for donating. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error.');
            }

            return $this->refresh();
        } else {
            return $this->render('donate', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionAuction()
    {
        $pageSize = 12;
        $query    = \backend\models\Auction::find()
            ->with(['product', 'product.image', 'product.category'])
            ->orderBy(['start_date' => SORT_ASC]);

        $pages = new \yii\data\Pagination([
            'totalCount'    => (clone $query)->count(),
            'pageSize'      => $pageSize,
            'pageSizeParam' => false,
        ]);

        $auctions = $query->offset($pages->offset)->limit($pages->limit)->all();

        $favoritedIds = [];
        if (!Yii::$app->user->isGuest) {
            $favoritedIds = Favorites::find()
                ->select('product_id')
                ->where(['user_id' => Yii::$app->user->id])
                ->column();
        }

        return $this->render('auction', [
            'auctions'     => $auctions,
            'pages'        => $pages,
            'favoritedIds' => $favoritedIds,
        ]);
    }
    
    public function actionBestOffer()
    {
        $pageSize   = 12;
        $totalCount = count(Product::findList(['best_offer' => 1]));
        $pages      = new \yii\data\Pagination([
            'totalCount'   => $totalCount,
            'pageSize'     => $pageSize,
            'pageSizeParam' => false,
        ]);

        $products = Product::findList([
            'best_offer' => 1,
            'limit'      => $pageSize,
            'offset'     => $pages->offset,
        ]);

        return $this->render('best-offer', [
            'products' => $products,
            'pages'    => $pages,
        ]);
    }
    
    public function actionMoreDetails()
    {
        // Latest local gold prices (most recent batch, not today-only)
        $latestDate = MetalPrices::find()
            ->select('DATE(created_at) as d')
            ->andWhere(['metal_id' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->scalar();

        $localGoldPrices = $latestDate
            ? MetalPrices::find()
                ->where(['metal_id' => 1])
                ->andWhere(['like', 'created_at', $latestDate])
                ->with(['metal', 'currency'])
                ->orderBy(['karat' => SORT_DESC])
                ->all()
            : [];

        // Global prices computed from the latest API snapshot
        $metalPriceApiData = MetalPriceReal::find()
            ->orderBy(['created_date' => SORT_DESC])
            ->one();

        $globalPrices = [];
        $spotPrice = null;
        if ($metalPriceApiData && empty($metalPriceApiData->request_error)) {
            $raw  = $metalPriceApiData->request_data;
            $data = is_array($raw) ? $raw : json_decode($raw, true);
            $buyPerGram  = round($data['bid'] / 31.1035, 4);
            $sellPerGram = round($data['ask'] / 31.1035, 4);

            $karats = [
                '999' => 0.999, '995' => 0.995, '958' => 0.958, '916' => 0.916,
                '900 - 21.6K' => 0.900, '875' => 0.875, '750' => 0.750,
                '585' => 0.585, '500 - 12K' => 0.500, '416 - 10K' => 0.416,
                '375 - 9K' => 0.375, '333' => 0.333,
            ];
            foreach ($karats as $label => $ratio) {
                $globalPrices[$label] = [
                    'buy'  => round($buyPerGram  * $ratio, 2),
                    'sell' => round($sellPerGram * $ratio, 2),
                ];
            }

            $spotPrice = [
                'metal'       => 'Gold',
                'price'       => round($data['price'] / 31.1035, 4),
                'change'      => $data['ch'] ?? null,
                'change_pct'  => $data['chp'] ?? null,
                'date'        => $metalPriceApiData->created_date,
                'price_gram_24k' => $data['price_gram_24k'] ?? null,
            ];
        }

        // Exchange rates from admin (most recent per currency)
        $exchangeRates = ExchangeRates::find()
            ->with('currency')
            ->orderBy(['updated_at' => SORT_DESC])
            ->all();

        return $this->render('moreDetails', [
            'localGoldPrices' => $localGoldPrices,
            'globalPrices'    => $globalPrices,
            'spotPrice'       => $spotPrice,
            'exchangeRates'   => $exchangeRates,
        ]);
    }

    public function actionBrands()
    {
        $filter = [];
        $letters = Yii::$app->request->get('letter');
        if ($letters) {
            $filter = ['letter' => Yii::$app->request->get('letter')];
        }
        $brands = Brand::findList(null, $filter);
        foreach ($brands as $key => $br) {
            $brands[$key]['countProduct'] = Brand::getProductCountByBrand($br['id'], true);
            $brands[$key]['products'] = Brand::getProductCountByBrand($br['id']);
        }
        return $this->render('brands', [
            'brands' => $brands
        ]);
    }

    public function actionChangeCurrency($currency)
    {
        $currency = Currency::find()->where(['id' => $currency])->one();
        $session = Yii::$app->session;
        if (!empty($currency)) {
            $currencySession = ['currenncyID' => $currency->id, 'exchange' => $currency->exchange_value];
            if ($session->has('currency')) {
                $currncyArray = $session->remove('currency');
            }
            $session->set('currency', $currencySession);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function createTree(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['id']])) {
                $l['child'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin($authkey = null)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        $model = new LoginForm();
        $modelSignup = new SignupForm();
        if (!is_null($authkey)) {
            $customer = Customer::findByPasswordResetToken($authkey);
            if ($customer) {
                $modelSignup->name = $customer->name;
                $modelSignup->surname = $customer->surname;
                $modelSignup->email = $customer->email;
                $modelSignup->name = $customer->name;
                $modelSignup->verifyToken = $authkey;
                Yii::$app->session->setFlash('notvalid', 'You have successfuly verified your email!');
                Yii::$app->session->setFlash('notvalid', 'Please type new password on Signup form to alow to enter your account');
            } else {
                Yii::$app->session->setFlash('error', 'Wrong password reset token.');
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            //$email = Yii::$app->request->post('LoginForm')['email'];
            if ($model->login()) {
                return $this->redirect(Url::previous());
            } elseif (User::$notverified) {
                Yii::$app->session->setFlash('notvalid', 'Please enter your email acocunt and verify your email');
                $model->sendEmailVerify($email);
                return $this->redirect('/site/login');
            } else {
                Yii::$app->session->setFlash('error', 'You typed not correct email or password');
                return $this->redirect('/site/login');
            }
        } else {
            return $this->render('login', [
                'model' => $model,
                'modelSignup' => $modelSignup,
            ]);
        }
    }


    public function actionLoginUser()
    {
        if (Yii::$app->request->isAjax) {
            $userInfo = Yii::$app->request->post('user_info');
            $userData = [];
            $socialId = isset($userInfo['response']) ? $userInfo['response'][0]['id'] : $userInfo['id'];
            $email = isset($userInfo['response']) ? $userInfo['response'][0]['email'] : $userInfo['email'];
            $userLogin = User::find()->where(['social_id' => $socialId])->one();
            if (empty($userLogin)) {
                $userLogin = User::find()->where(['email' => $email])->one();
            }
            if (!empty($userLogin)) {
                if (!empty($userLogin)) {
                    Yii::$app->user->login($userLogin);
                    echo json_encode(['redirectUrl' => Url::previous(), 'success' => true]);
                    exit();
                }
            } else {
                if (isset($userInfo['response'])) {
                    $userData['name'] = $userInfo['response'][0]['first_name'];
                    $userData['surname'] = $userInfo['response'][0]['last_name'];
                    $userData['email'] = isset($userData['response'][0]['email']) ? $userData['response'][0]['email'] : '';
                    $user = new User();
                    $user->username = mb_strtolower($userData['name']) . $userInfo['response'][0]['id'];
                    $user->role = 20;
                    $user->email = isset($userData['response'][0]['email']) ? $userData['response'][0]['email'] : '';
                    $user->social_type = 'facebook';
                    $user->social_id = $userInfo['response'][0]['id'];
                    $password = Yii::$app->security->generateRandomString(6);
                    $user->setPassword($password);
                    $user->generateAuthKey();
                    $userData['social_user_name'] = $userInfo['response'][0]['first_name'] . ' ' . $userInfo['response'][0]['last_name'];
                } else {
                    $user_fio = explode(' ', $userInfo['name']);
                    $userData['name'] = $user_fio[0];
                    $userData['email'] = $userInfo['email'];
                    $userData['surname'] = $user_fio[1];
                    $userData['social_user_name'] = $userInfo['name'];
                    $user = new User();
                    $user->username = mb_strtolower($userData['name']) . $userInfo['id'];
                    $user->role = 20;
                    $user->email = isset($userData['email']) ? $userData['email'] : '';
                    $user->social_type = 'facebook';
                    $user->social_id = $userInfo['id'];
                    $password = Yii::$app->security->generateRandomString(6);
                    $user->setPassword($password);
                    $user->generateAuthKey();
                }

                if ($user->save()) {
                    $customer = new Customer();
                    $customer->name = $userData['name'];
                    $customer->surname = $userData['surname'];
                    $customer->email = isset($userData['email']) ? $userData['email'] : '';
                    $customer->user_id = $user->id;
                    $customer->last_ip = \Yii::$app->request->userIP;
                    $customer->status = 0;
                    $customer->social_user_name = $userData['social_user_name'];
                    $customer->auth_token = '';
                    if ($customer->save(false)) {
                        $userLogin = User::find()->where(['id' => $user->id])->one();
                        if (!empty($userLogin)) {
                            Yii::$app->user->login($userLogin);
                            if ($customer->email != '') {
                                echo json_encode(['redirectUrl' => Url::previous(), 'success' => true]);
                                exit();
                            } else {
                                echo json_encode(['redirectUrl' => '/site/zapolnit-email', 'success' => true]);
                                exit();
                            }
                        }
                        /* $userData = ['email' => $customer->email, 'password' => $password];
                          if ($this->sendEmail($customer->email, 'Invite', $userData)) {
                          Yii::$app->session->setFlash('success', "Your login data sent to your email, please enter to your email to see");
                          } */
                    }
                } else {
                    echo json_encode(['redirectUrl' => Url::previous(), 'success' => false]);
                    exit();
                }
            }
        }
    }

    public function sendEmail($to, $subject, $data)
    {
        $username = ucfirst($data['email']);
        $password = $data['password'];
        $name = preg_replace("/[0-9]+/", '', $username);
        $message = "Hello $name!<br/><br/>
        Your username is $username,<br/>
             password is $password<br/>
         You was added as Customer in our site. You'll love it!";
        return Yii::$app
            ->mailer
            ->compose('email-layout', ['content' => $message])
            ->setFrom(['admin-odenson@test.com' => Yii::$app->name])
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }

    public function actionCart()
    {
        return $this->render('/cart/list');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail('')) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionPage($tag)
    {
        $page = Pages::find()->where(['route_name' => $tag])->one();
        return $this->render('page', ['page' => $page]);
    }

    public function actionAboutUs()
    {
        $page = Aboutus::findOne(1);
        return $this->render('about-us', ['page' => $page]);
    }

    public function actionNewsList()
    {
        $news = News::find()
            ->where(['status' => 1])
            ->orderBy(['created_date' => SORT_DESC])
            ->all();
        $categories = NewsCategory::find()->all();
        return $this->render('news-list', [
            'news'       => $news,
            'categories' => $categories,
        ]);
    }

    public function actionNews($id)
    {
        $news = News::findOne(['id' => $id, 'status' => 1]);
        if (!$news) {
            throw new \yii\web\NotFoundHttpException('News not found.');
        }
        return $this->render('news', ['news' => $news]);
    }

    public function actionNewsByCategory($category)
    {
        $category = NewsCategory::find()->where(['route_name' => $category])->one();
        if (!$category) {
            throw new \yii\web\NotFoundHttpException('Category not found.');
        }
        $news = News::find()->where(['status' => 1, 'category_id' => $category->id])->orderBy(['created_date' => SORT_DESC])->all();
        return $this->render('news-by-category', ['news' => $news, 'category' => $category]);
    }

    /**
     * Displays faq page.
     *
     * @return mixed
     */
    public function actionFaq()
    {
        return $this->render('/faq/faq');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
                try {
                // $verifyToken = Yii::$app->request->post('verifyToken');
                // $customer = Customer::findByPasswordResetToken($verifyToken);
                // $model->load(Yii::$app->request->post());
                // $model->username = $model->name . time();;
                // if ($customer) {
                //     $customer->auth_token = "";
                //     if ($customer->save()) {
                //         $user = $model->getNewUser();
                //         if (Yii::$app->getUser()->login($user)) {
                //             Yii::$app->session->setFlash('success', 'You should type your contact info');
                //             return $this->redirect('/user/profile');
                //         }
                //     }
                // } else {
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        Yii::$app->session->setFlash('success', 'You should type your contact info');
                        return $this->redirect('/user/profile');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Registration failed. Please check your details and try again.');
                    return $this->redirect(['/site/signup']);
                }
                //}
            } catch (\Exception $e) {
                print_r($e);
                die;
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
