<?php

namespace app\jobs;

use app\models\Extension;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;
use yii\web\HttpException;

class ExtensionImportJob extends BaseObject implements RetryableJobInterface
{
    public $extensionId;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $extension = Extension::findOne($this->extensionId);
        if (!$extension || ($extension->update_status != Extension::UPDATE_STATUS_NEW && $extension->update_status != Extension::UPDATE_STATUS_EXPIRED)) {
            echo "skipping update from Packagist.\n";
            return;
        }

        echo "updating extension {$extension->name} from Packagist...";
        $extension->populateFromPackagist();
        echo "done.\n";
        $extension->update_status = Extension::UPDATE_STATUS_UPTODATE;
        $extension->update_time = new \yii\db\Expression('NOW()');
        // do not update timestamps and blame on automated updates
        $extension->detachBehavior('blameable');
        $extension->detachBehavior('timestamp');
        $extension->detachBehavior('timestamp');
        $extension->save(false);

        if ($extension->isOfficialExtension) {
            echo "updating extension {$extension->name} docs...\n";
            $failed = false;
            passthru(\Yii::getAlias('@app/yii') . ' guide/extension ' . escapeshellarg($extension->name) . ' --interactive=0', $exitCode);
            if ($exitCode != 0) {
                $failed = true;
            }
            passthru(\Yii::getAlias('@app/yii') . ' api/extension ' . escapeshellarg($extension->name) . ' --interactive=0', $exitCode);
            if ($exitCode != 0) {
                $failed = true;
            }
            passthru(\Yii::getAlias('@app/yii') . ' guide/extension ' . escapeshellarg($extension->name) . ' --interactive=0', $exitCode);
            if ($exitCode != 0) {
                $failed = true;
            }

            if ($failed) {
                throw new \Exception("Failed to generate docs for extension {$extension->name}.");
            }
            echo "done.\n";
        }
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @param int $attempt number
     * @param \Exception $error from last execute of the job
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return $error instanceof HttpException && $error->statusCode >= 500;
    }
}
