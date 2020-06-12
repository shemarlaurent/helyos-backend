<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AbyssForumMessage extends Model
{
    protected $guarded = [];

    protected $with = ['user'];

    /** a collection of a forums message
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forum()
    {
        return $this->belongsTo(AbyssForum::class);
    }

    /** a collection
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AbyssUser::class, 'abyss_user_id');
    }

}
