
<!-- Форма -->
<div class="row">
    <div class="col-md-6">


        <div class="alert alert-success" role="alert">

            <?
            $df = &$data['dataFormAdmin'];
            $sn = &$data['session']['home']['post'];
            $name = isset($df['name']) ? $df['name'] : $sn['name'] ?? '' ;
            $email = isset($df['email']) ? $df['email'] : $sn['email'] ?? '';
            $message = isset($df['message']) ? $df['message'] : $sn['message'] ?? '';
            ?>
            <form class="row g-3" method="POST" action="/home">
                <div class="col-md-6">
                    <label for="inputName4" class="form-label">Name</label>
                    <input type="Name" class="form-control" value="<?=$name ?>" name="name" id="inputName4">
                    <small id="nameHelpBlock" class="form-text text-danger">
                        <?=$data['session']['home']['formMessage']['name'] ?? ''?>
                    </small>
                </div>

                <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Email</label>
                    <input type="text" class="form-control" value="<?=$email?>" name="email" id="inputEmail4">
                    <small id="emailHelpBlock" class="form-text text-danger">
                        <?=$data['session']['home']['formMessage']['email'] ?? ''?>
                    </small>
                </div>


                <div class="col-12">
                    <label for="Textarea1" class="form-label">Message</label>
                    <? $message = str_replace('\r\n', "\r\n",$message); ?>
                    <textarea class="form-control" name="message" id="Textarea1" rows="3"><?=$message?></textarea>
                    <small id="msgHelpBlock" class="form-text text-danger">
                        <?=$data['session']['home']['formMessage']['message'] ?? ''?>
                    </small>
                </div>

                <div class="col-12">
                    <? if(isset($data['session']['isAdmin']) && $data['session']['isAdmin'] == true): ?>
                        <? $checked = $df['status'] == 1 ? 'checked' : '' ?>
                        <div class="form-check">
                            <input type="checkbox" name="status" class="form-check-input" <?=$checked?>>
                            <input type="hidden" name="id" value="<?=$df['id'] ?? ''?>" >
                            <label class="form-check-label" for="exampleCheck1">выполнено</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    <? else: ?>
                        <input type="hidden" name="status" value="0">
                        <button type="submit" class="btn btn-primary">Add</button>
                    <? endif; ?>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Форма -->



<div class="row my-3">
    <div class="col-md-2"><strong>Сортировка</strong></div>
    <? $vars = CalculateUrlVars::getParamsString($data['params']['urlVars'], 'name', ['email', 'status', 'page'])?>
    <div class="col-md-2"><a href="/home/<?=$vars?>">По имени</a></div>

    <? $vars = CalculateUrlVars::getParamsString($data['params']['urlVars'], 'email', ['name', 'status', 'page'])?>
    <div class="col-md-2"><a href="/home/<?=$vars?>">По Email</a></div>

    <? $vars = CalculateUrlVars::getParamsString($data['params']['urlVars'], 'status', ['name', 'email', 'page'])?>
    <div class="col-md-2"><a href="/home/<?=$vars?>">По статусу задач</a></div>
</div>

<? foreach ($data['messages'] as $item):?>

<div class="card">
    <div class="card-header">
            <div class="row">
                <div class="col-md-11">
                    <strong>имя пользователя:</strong>
                    <?if(isset($data['session']['isAdmin']) && $data['session']['isAdmin'] == true):?>
                        <a href="/home/message=<?=$item['id']?>"><?=$item['name']?></a>
                    <?else:?>
                        <?=$item['name']?>
                    <?endif;?>
                </div>
                <div class="col-md-1">

                </div>
            </div>
        </span>
    </div>
    <div class="card-body">
        <p><strong>email:</strong> <?=$item['email']?></p>
        <strong>текст задачи:</strong>
        <p class="card-text"><?=$item['message']?></p>
        <p><strong>статус:</strong> <?=!$item['status'] ? 'Не выполнено' : 'Выполнено'?></p>
    <p>
        <? if($item['isAdminWrite'] == 1): ?>
            отредактировано администратором
        <? endif; ?>
    </p>
    </div>
</div>
<? endforeach; ?>


<div class="my-3">
    <nav aria-label="Page navigation example">
        <ul class="pagination">

            <? $d = $data['params']['urlVars']; ?>
            <?$d['page'] = $data['numPage'] > 1 ? $data['numPage'] - 1 : $data['numPage']?>

            <li class="page-item">
                <a class="page-link" href="/home/<?=CalculateUrlVars::createParamString($d)?>">Previous</a>
            </li>


            <? for($i = 0; $i < $data['countPage']; $i++): ?>
                <? if($i+1<= $data['countPage']): ?>
                    <li class="page-item">
                        <? $d['page'] = $i+1?>
                        <a class="page-link" href="/home/<?=CalculateUrlVars::createParamString($d)?>">
                            <?=$i+1?>
                        </a>
                    </li>
                <?endif;?>
            <? endfor; ?>

            <? $d['page'] = $data['numPage']+1 > $data['countPage'] ? $data['countPage'] : $data['numPage']+1?>
            <li class="page-item">
                <a class="page-link" href="/home/<?=CalculateUrlVars::createParamString($d)?>">Next</a>
            </li>
        </ul>
    </nav>
</div>



