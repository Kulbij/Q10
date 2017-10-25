<div class="col-lg-12">
    <table class="table table-bordered" id="seller-info">
        <tbody>

        <?php if (sizeof($data) > 0) : ?>
            <?php foreach ($data as $key => $item) : ?>

                <?php if ($item['type'] == 'text') : ?>
                    <tr>
                        <td class="<?=$item['class'];?>"><?=$item['label'];?></td>
                        <td>
                            <?php if (isset($item['preloader']) && $item['preloader'] === true) : ?>
                                <div<?php if (isset($item['field'])) : ?> id="<?=$item['field']; ?>" <?php endif; ?>class="loadermini"><input type="hidden" name="<?=$item['field']; ?>" value="<?=$item['value'];?>"></div>
                            <?php else : ?>
                                <?=$item['value'];?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php elseif ($item['type'] == 'table') : ?>
                    <tr>
                        <td class="<?=$item['class'];?>"><?=$item['label'];?></td>
                        <td>
                        <?php if (sizeof($item['values']) > 0) : ?>
                            <table class="table">
                                <thead>
                                  <tr>
                                    <?php foreach ($item['values'] as $value) : ?>
                                        <th><?=$value['label']; ?></th>
                                    <?php endforeach; ?>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <?php foreach ($item['values'] as $value) : ?>
                                        <td><?=$value['value']; ?></td>
                                    <?php endforeach; ?>
                                  </tr>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php elseif ($item['type'] == 'link') : ?>
                    <tr>
                        <td class="<?=$item['class'];?>"><?=$item['label'];?></td>
                        <td><div class="seller_reviews">
                        <a target="_blank" href="<?=$item['href'];?>"><?=$item['value'];?></a></div></td>
                    </tr>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php else : ?>
            Nothing found
        <?php endif; ?>
        </tbody>
    </table>
</div>