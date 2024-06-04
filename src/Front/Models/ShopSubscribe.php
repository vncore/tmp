<?php
#Vncore/Core/Front/Models/ShopSubscribe.php
namespace Vncore\Core\Front\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSubscribe extends Model
{
    use \Vncore\Core\Front\Models\ModelTrait;
    use \Vncore\Core\Front\Models\UuidTrait;

    public $table = SC_DB_PREFIX.'shop_subscribe';
    protected $guarded      = [];
    protected $connection = SC_CONNECTION;

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($model) {
            //
            }
        );
        //Uuid
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = vc_generate_id($type = 'shop_subscribe');
            }
        });
    }
}
